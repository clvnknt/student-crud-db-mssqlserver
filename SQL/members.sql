GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Students](
    [StudentID] [int] IDENTITY(1,1) NOT NULL,
    [FirstName] [varchar](50) NULL,
    [LastName] [varchar](50) NULL,
    [Email] [varchar](50) NULL,
    [Section] [varchar](50) NULL,
    [Created] [datetime] NULL
) ON [PRIMARY]
GO