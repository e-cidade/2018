<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: agua
//CLASSE DA ENTIDADE aguaplanilha
class cl_aguaplanilha { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $x24_matric = 0; 
   var $x24_exerc = 0; 
   var $x24_mes = 0; 
   var $x24_nome = null; 
   var $x24_codrua = 0; 
   var $x24_nomerua = null; 
   var $x24_numero = 0; 
   var $x24_complemento = null; 
   var $x24_zona = 0; 
   var $x24_rota = 0; 
   var $x24_pagina = 0; 
   var $x24_nrohidro = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x24_matric = int4 = Matrícula 
                 x24_exerc = int4 = Ano 
                 x24_mes = int4 = Mes 
                 x24_nome = varchar(40) = Nome 
                 x24_codrua = int4 = cód. Logradouro 
                 x24_nomerua = varchar(40) = Nome Rua 
                 x24_numero = int4 = Numero 
                 x24_complemento = varchar(10) = Complemento 
                 x24_zona = int4 = Zona 
                 x24_rota = int4 = Rota 
                 x24_pagina = int4 = Pagina 
                 x24_nrohidro = varchar(15) = Hidrometro 
                 ";
   //funcao construtor da classe 
   function cl_aguaplanilha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaplanilha"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->x24_matric = ($this->x24_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_matric"]:$this->x24_matric);
       $this->x24_exerc = ($this->x24_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_exerc"]:$this->x24_exerc);
       $this->x24_mes = ($this->x24_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_mes"]:$this->x24_mes);
       $this->x24_nome = ($this->x24_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_nome"]:$this->x24_nome);
       $this->x24_codrua = ($this->x24_codrua == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_codrua"]:$this->x24_codrua);
       $this->x24_nomerua = ($this->x24_nomerua == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_nomerua"]:$this->x24_nomerua);
       $this->x24_numero = ($this->x24_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_numero"]:$this->x24_numero);
       $this->x24_complemento = ($this->x24_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_complemento"]:$this->x24_complemento);
       $this->x24_zona = ($this->x24_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_zona"]:$this->x24_zona);
       $this->x24_rota = ($this->x24_rota == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_rota"]:$this->x24_rota);
       $this->x24_pagina = ($this->x24_pagina == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_pagina"]:$this->x24_pagina);
       $this->x24_nrohidro = ($this->x24_nrohidro == ""?@$GLOBALS["HTTP_POST_VARS"]["x24_nrohidro"]:$this->x24_nrohidro);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->x24_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "x24_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_exerc == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "x24_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_mes == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "x24_mes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "x24_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_codrua == null ){ 
       $this->erro_sql = " Campo cód. Logradouro nao Informado.";
       $this->erro_campo = "x24_codrua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_nomerua == null ){ 
       $this->erro_sql = " Campo Nome Rua nao Informado.";
       $this->erro_campo = "x24_nomerua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_numero == null ){ 
       $this->erro_sql = " Campo Numero nao Informado.";
       $this->erro_campo = "x24_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_complemento == null ){ 
       $this->erro_sql = " Campo Complemento nao Informado.";
       $this->erro_campo = "x24_complemento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_zona == null ){ 
       $this->erro_sql = " Campo Zona nao Informado.";
       $this->erro_campo = "x24_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_rota == null ){ 
       $this->erro_sql = " Campo Rota nao Informado.";
       $this->erro_campo = "x24_rota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_pagina == null ){ 
       $this->erro_sql = " Campo Pagina nao Informado.";
       $this->erro_campo = "x24_pagina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x24_nrohidro == null ){ 
       $this->erro_sql = " Campo Hidrometro nao Informado.";
       $this->erro_campo = "x24_nrohidro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaplanilha(
                                       x24_matric 
                                      ,x24_exerc 
                                      ,x24_mes 
                                      ,x24_nome 
                                      ,x24_codrua 
                                      ,x24_nomerua 
                                      ,x24_numero 
                                      ,x24_complemento 
                                      ,x24_zona 
                                      ,x24_rota 
                                      ,x24_pagina 
                                      ,x24_nrohidro 
                       )
                values (
                                $this->x24_matric 
                               ,$this->x24_exerc 
                               ,$this->x24_mes 
                               ,'$this->x24_nome' 
                               ,$this->x24_codrua 
                               ,'$this->x24_nomerua' 
                               ,$this->x24_numero 
                               ,'$this->x24_complemento' 
                               ,$this->x24_zona 
                               ,$this->x24_rota 
                               ,$this->x24_pagina 
                               ,'$this->x24_nrohidro' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Planilha de Leitura () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Planilha de Leitura já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Planilha de Leitura () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update aguaplanilha set ";
     $virgula = "";
     if(trim($this->x24_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_matric"])){ 
       $sql  .= $virgula." x24_matric = $this->x24_matric ";
       $virgula = ",";
       if(trim($this->x24_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "x24_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_exerc"])){ 
       $sql  .= $virgula." x24_exerc = $this->x24_exerc ";
       $virgula = ",";
       if(trim($this->x24_exerc) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "x24_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_mes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_mes"])){ 
       $sql  .= $virgula." x24_mes = $this->x24_mes ";
       $virgula = ",";
       if(trim($this->x24_mes) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "x24_mes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_nome"])){ 
       $sql  .= $virgula." x24_nome = '$this->x24_nome' ";
       $virgula = ",";
       if(trim($this->x24_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "x24_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_codrua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_codrua"])){ 
       $sql  .= $virgula." x24_codrua = $this->x24_codrua ";
       $virgula = ",";
       if(trim($this->x24_codrua) == null ){ 
         $this->erro_sql = " Campo cód. Logradouro nao Informado.";
         $this->erro_campo = "x24_codrua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_nomerua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_nomerua"])){ 
       $sql  .= $virgula." x24_nomerua = '$this->x24_nomerua' ";
       $virgula = ",";
       if(trim($this->x24_nomerua) == null ){ 
         $this->erro_sql = " Campo Nome Rua nao Informado.";
         $this->erro_campo = "x24_nomerua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_numero"])){ 
       $sql  .= $virgula." x24_numero = $this->x24_numero ";
       $virgula = ",";
       if(trim($this->x24_numero) == null ){ 
         $this->erro_sql = " Campo Numero nao Informado.";
         $this->erro_campo = "x24_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_complemento"])){ 
       $sql  .= $virgula." x24_complemento = '$this->x24_complemento' ";
       $virgula = ",";
       if(trim($this->x24_complemento) == null ){ 
         $this->erro_sql = " Campo Complemento nao Informado.";
         $this->erro_campo = "x24_complemento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_zona"])){ 
       $sql  .= $virgula." x24_zona = $this->x24_zona ";
       $virgula = ",";
       if(trim($this->x24_zona) == null ){ 
         $this->erro_sql = " Campo Zona nao Informado.";
         $this->erro_campo = "x24_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_rota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_rota"])){ 
       $sql  .= $virgula." x24_rota = $this->x24_rota ";
       $virgula = ",";
       if(trim($this->x24_rota) == null ){ 
         $this->erro_sql = " Campo Rota nao Informado.";
         $this->erro_campo = "x24_rota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_pagina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_pagina"])){ 
       $sql  .= $virgula." x24_pagina = $this->x24_pagina ";
       $virgula = ",";
       if(trim($this->x24_pagina) == null ){ 
         $this->erro_sql = " Campo Pagina nao Informado.";
         $this->erro_campo = "x24_pagina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x24_nrohidro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x24_nrohidro"])){ 
       $sql  .= $virgula." x24_nrohidro = '$this->x24_nrohidro' ";
       $virgula = ",";
       if(trim($this->x24_nrohidro) == null ){ 
         $this->erro_sql = " Campo Hidrometro nao Informado.";
         $this->erro_campo = "x24_nrohidro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Planilha de Leitura nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Planilha de Leitura nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from aguaplanilha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Planilha de Leitura nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Planilha de Leitura nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:aguaplanilha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="aguaplanilha.oid,*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from aguaplanilha ";
     $sql .= "      inner join aguabase  on  aguabase.x01_matric = aguaplanilha.x24_matric";
     $sql .= "      inner join bairro  on  bairro.j13_codi = aguabase.x01_codbairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = aguabase.x01_codrua";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguabase.x01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where aguaplanilha.oid = '$oid'";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from aguaplanilha ";
     $sql2 = "";
     if($dbwhere==""){
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>