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

//MODULO: pessoal
//CLASSE DA ENTIDADE recfgts
class cl_recfgts { 
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
   var $r58_regist = 0; 
   var $r58_subpes = null; 
   var $r58_dtpag_dia = null; 
   var $r58_dtpag_mes = null; 
   var $r58_dtpag_ano = null; 
   var $r58_dtpag = null; 
   var $r58_base = 0; 
   var $r58_base13 = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r58_regist = int4 = Codigo do Funcionario 
                 r58_subpes = char(     7) = ano/mes ref.recolhimento 
                 r58_dtpag = date = Data do pagamento 
                 r58_base = float8 = valor da base de fgts 
                 r58_base13 = float8 = valor da base fgts 13.sal. 
                 ";
   //funcao construtor da classe 
   function cl_recfgts() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recfgts"); 
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
       $this->r58_regist = ($this->r58_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_regist"]:$this->r58_regist);
       $this->r58_subpes = ($this->r58_subpes == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_subpes"]:$this->r58_subpes);
       if($this->r58_dtpag == ""){
         $this->r58_dtpag_dia = ($this->r58_dtpag_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_dtpag_dia"]:$this->r58_dtpag_dia);
         $this->r58_dtpag_mes = ($this->r58_dtpag_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_dtpag_mes"]:$this->r58_dtpag_mes);
         $this->r58_dtpag_ano = ($this->r58_dtpag_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_dtpag_ano"]:$this->r58_dtpag_ano);
         if($this->r58_dtpag_dia != ""){
            $this->r58_dtpag = $this->r58_dtpag_ano."-".$this->r58_dtpag_mes."-".$this->r58_dtpag_dia;
         }
       }
       $this->r58_base = ($this->r58_base == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_base"]:$this->r58_base);
       $this->r58_base13 = ($this->r58_base13 == ""?@$GLOBALS["HTTP_POST_VARS"]["r58_base13"]:$this->r58_base13);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->r58_regist == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
       $this->erro_campo = "r58_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r58_subpes == null ){ 
       $this->erro_sql = " Campo ano/mes ref.recolhimento nao Informado.";
       $this->erro_campo = "r58_subpes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r58_dtpag == null ){ 
       $this->erro_sql = " Campo Data do pagamento nao Informado.";
       $this->erro_campo = "r58_dtpag_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r58_base == null ){ 
       $this->erro_sql = " Campo valor da base de fgts nao Informado.";
       $this->erro_campo = "r58_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r58_base13 == null ){ 
       $this->erro_sql = " Campo valor da base fgts 13.sal. nao Informado.";
       $this->erro_campo = "r58_base13";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recfgts(
                                       r58_regist 
                                      ,r58_subpes 
                                      ,r58_dtpag 
                                      ,r58_base 
                                      ,r58_base13 
                       )
                values (
                                $this->r58_regist 
                               ,'$this->r58_subpes' 
                               ,".($this->r58_dtpag == "null" || $this->r58_dtpag == ""?"null":"'".$this->r58_dtpag."'")." 
                               ,$this->r58_base 
                               ,$this->r58_base13 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "controle de recolhimento de fgts                   () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "controle de recolhimento de fgts                   já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "controle de recolhimento de fgts                   () nao Incluído. Inclusao Abortada.";
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
     $sql = " update recfgts set ";
     $virgula = "";
     if(trim($this->r58_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r58_regist"])){ 
       $sql  .= $virgula." r58_regist = $this->r58_regist ";
       $virgula = ",";
       if(trim($this->r58_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r58_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r58_subpes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r58_subpes"])){ 
       $sql  .= $virgula." r58_subpes = '$this->r58_subpes' ";
       $virgula = ",";
       if(trim($this->r58_subpes) == null ){ 
         $this->erro_sql = " Campo ano/mes ref.recolhimento nao Informado.";
         $this->erro_campo = "r58_subpes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r58_dtpag)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r58_dtpag_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["r58_dtpag_dia"] !="") ){ 
       $sql  .= $virgula." r58_dtpag = '$this->r58_dtpag' ";
       $virgula = ",";
       if(trim($this->r58_dtpag) == null ){ 
         $this->erro_sql = " Campo Data do pagamento nao Informado.";
         $this->erro_campo = "r58_dtpag_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["r58_dtpag_dia"])){ 
         $sql  .= $virgula." r58_dtpag = null ";
         $virgula = ",";
         if(trim($this->r58_dtpag) == null ){ 
           $this->erro_sql = " Campo Data do pagamento nao Informado.";
           $this->erro_campo = "r58_dtpag_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->r58_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r58_base"])){ 
       $sql  .= $virgula." r58_base = $this->r58_base ";
       $virgula = ",";
       if(trim($this->r58_base) == null ){ 
         $this->erro_sql = " Campo valor da base de fgts nao Informado.";
         $this->erro_campo = "r58_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r58_base13)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r58_base13"])){ 
       $sql  .= $virgula." r58_base13 = $this->r58_base13 ";
       $virgula = ",";
       if(trim($this->r58_base13) == null ){ 
         $this->erro_sql = " Campo valor da base fgts 13.sal. nao Informado.";
         $this->erro_campo = "r58_base13";
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
       $this->erro_sql   = "controle de recolhimento de fgts                   nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "controle de recolhimento de fgts                   nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from recfgts
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
       $this->erro_sql   = "controle de recolhimento de fgts                   nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "controle de recolhimento de fgts                   nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:recfgts";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>