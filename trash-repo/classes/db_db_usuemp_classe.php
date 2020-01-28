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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_usuemp
class cl_db_usuemp { 
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
   var $z97_anousu = 0; 
   var $z97_usuario = 0; 
   var $z97_orgao = null; 
   var $z97_unida = null; 
   var $z97_funcao = null; 
   var $z97_subfuncao = null; 
   var $z97_progra = null; 
   var $z97_proati = null; 
   var $z97_despes = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 z97_anousu = int4 = Exercício 
                 z97_usuario = int4 = Código 
                 z97_orgao = char(2) = Órgão 
                 z97_unida = char(2) = Unidade 
                 z97_funcao = char(2) = Função 
                 z97_subfuncao = char(3) = Subfunção 
                 z97_progra = char(4) = Programa 
                 z97_proati = char(4) = Projeto/atividade 
                 z97_despes = char(12) = Elementos 
                 ";
   //funcao construtor da classe 
   function cl_db_usuemp() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_usuemp"); 
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
       $this->z97_anousu = ($this->z97_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_anousu"]:$this->z97_anousu);
       $this->z97_usuario = ($this->z97_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_usuario"]:$this->z97_usuario);
       $this->z97_orgao = ($this->z97_orgao == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_orgao"]:$this->z97_orgao);
       $this->z97_unida = ($this->z97_unida == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_unida"]:$this->z97_unida);
       $this->z97_funcao = ($this->z97_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_funcao"]:$this->z97_funcao);
       $this->z97_subfuncao = ($this->z97_subfuncao == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_subfuncao"]:$this->z97_subfuncao);
       $this->z97_progra = ($this->z97_progra == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_progra"]:$this->z97_progra);
       $this->z97_proati = ($this->z97_proati == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_proati"]:$this->z97_proati);
       $this->z97_despes = ($this->z97_despes == ""?@$GLOBALS["HTTP_POST_VARS"]["z97_despes"]:$this->z97_despes);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->z97_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "z97_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_usuario == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "z97_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_orgao == null ){ 
       $this->erro_sql = " Campo Órgão nao Informado.";
       $this->erro_campo = "z97_orgao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_unida == null ){ 
       $this->erro_sql = " Campo Unidade nao Informado.";
       $this->erro_campo = "z97_unida";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_funcao == null ){ 
       $this->erro_sql = " Campo Função nao Informado.";
       $this->erro_campo = "z97_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_subfuncao == null ){ 
       $this->erro_sql = " Campo Subfunção nao Informado.";
       $this->erro_campo = "z97_subfuncao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_progra == null ){ 
       $this->erro_sql = " Campo Programa nao Informado.";
       $this->erro_campo = "z97_progra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_proati == null ){ 
       $this->erro_sql = " Campo Projeto/atividade nao Informado.";
       $this->erro_campo = "z97_proati";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->z97_despes == null ){ 
       $this->erro_sql = " Campo Elementos nao Informado.";
       $this->erro_campo = "z97_despes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_usuemp(
                                       z97_anousu 
                                      ,z97_usuario 
                                      ,z97_orgao 
                                      ,z97_unida 
                                      ,z97_funcao 
                                      ,z97_subfuncao 
                                      ,z97_progra 
                                      ,z97_proati 
                                      ,z97_despes 
                       )
                values (
                                $this->z97_anousu 
                               ,$this->z97_usuario 
                               ,'$this->z97_orgao' 
                               ,'$this->z97_unida' 
                               ,'$this->z97_funcao' 
                               ,'$this->z97_subfuncao' 
                               ,'$this->z97_progra' 
                               ,'$this->z97_proati' 
                               ,'$this->z97_despes' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Permissão para Empenho  () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Permissão para Empenho  já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Permissão para Empenho  () nao Incluído. Inclusao Abortada.";
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
     $sql = " update db_usuemp set ";
     $virgula = "";
     if(trim($this->z97_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_anousu"])){ 
       $sql  .= $virgula." z97_anousu = $this->z97_anousu ";
       $virgula = ",";
       if(trim($this->z97_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "z97_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_usuario"])){ 
       $sql  .= $virgula." z97_usuario = $this->z97_usuario ";
       $virgula = ",";
       if(trim($this->z97_usuario) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "z97_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_orgao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_orgao"])){ 
       $sql  .= $virgula." z97_orgao = '$this->z97_orgao' ";
       $virgula = ",";
       if(trim($this->z97_orgao) == null ){ 
         $this->erro_sql = " Campo Órgão nao Informado.";
         $this->erro_campo = "z97_orgao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_unida)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_unida"])){ 
       $sql  .= $virgula." z97_unida = '$this->z97_unida' ";
       $virgula = ",";
       if(trim($this->z97_unida) == null ){ 
         $this->erro_sql = " Campo Unidade nao Informado.";
         $this->erro_campo = "z97_unida";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_funcao"])){ 
       $sql  .= $virgula." z97_funcao = '$this->z97_funcao' ";
       $virgula = ",";
       if(trim($this->z97_funcao) == null ){ 
         $this->erro_sql = " Campo Função nao Informado.";
         $this->erro_campo = "z97_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_subfuncao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_subfuncao"])){ 
       $sql  .= $virgula." z97_subfuncao = '$this->z97_subfuncao' ";
       $virgula = ",";
       if(trim($this->z97_subfuncao) == null ){ 
         $this->erro_sql = " Campo Subfunção nao Informado.";
         $this->erro_campo = "z97_subfuncao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_progra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_progra"])){ 
       $sql  .= $virgula." z97_progra = '$this->z97_progra' ";
       $virgula = ",";
       if(trim($this->z97_progra) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "z97_progra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_proati)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_proati"])){ 
       $sql  .= $virgula." z97_proati = '$this->z97_proati' ";
       $virgula = ",";
       if(trim($this->z97_proati) == null ){ 
         $this->erro_sql = " Campo Projeto/atividade nao Informado.";
         $this->erro_campo = "z97_proati";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->z97_despes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["z97_despes"])){ 
       $sql  .= $virgula." z97_despes = '$this->z97_despes' ";
       $virgula = ",";
       if(trim($this->z97_despes) == null ){ 
         $this->erro_sql = " Campo Elementos nao Informado.";
         $this->erro_campo = "z97_despes";
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
       $this->erro_sql   = "Permissão para Empenho  nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão para Empenho  nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from db_usuemp
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
       $this->erro_sql   = "Permissão para Empenho  nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Permissão para Empenho  nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:db_usuemp";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>