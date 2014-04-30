<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: orcamento
//CLASSE DA ENTIDADE orcprograma
class cl_orcprograma { 
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
   var $o54_anousu = 0; 
   var $o54_programa = 0; 
   var $o54_descr = null; 
   var $o54_codtri = null; 
   var $o54_finali = null; 
   var $o54_problema = null; 
   var $o54_publicoalvo = null; 
   var $o54_justificativa = null; 
   var $o54_objsetorassociado = null; 
   var $o54_tipoprograma = 0; 
   var $o54_estrategiaimp = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 o54_anousu = int4 = Exercício 
                 o54_programa = int4 = Programa 
                 o54_descr = varchar(40) = Descrição 
                 o54_codtri = varchar(10) = Código tribunal 
                 o54_finali = text = Finalidade 
                 o54_problema = text = Problema 
                 o54_publicoalvo = text = Público Alvo 
                 o54_justificativa = text = Justificativa 
                 o54_objsetorassociado = text = Objetivo Setor Associado 
                 o54_tipoprograma = int4 = Tipo de Programa 
                 o54_estrategiaimp = text = Estratégia de Implementação do Programa 
                 ";
   //funcao construtor da classe 
   function cl_orcprograma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("orcprograma"); 
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
       $this->o54_anousu = ($this->o54_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_anousu"]:$this->o54_anousu);
       $this->o54_programa = ($this->o54_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_programa"]:$this->o54_programa);
       $this->o54_descr = ($this->o54_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_descr"]:$this->o54_descr);
       $this->o54_codtri = ($this->o54_codtri == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_codtri"]:$this->o54_codtri);
       $this->o54_finali = ($this->o54_finali == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_finali"]:$this->o54_finali);
       $this->o54_problema = ($this->o54_problema == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_problema"]:$this->o54_problema);
       $this->o54_publicoalvo = ($this->o54_publicoalvo == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_publicoalvo"]:$this->o54_publicoalvo);
       $this->o54_justificativa = ($this->o54_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_justificativa"]:$this->o54_justificativa);
       $this->o54_objsetorassociado = ($this->o54_objsetorassociado == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_objsetorassociado"]:$this->o54_objsetorassociado);
       $this->o54_tipoprograma = ($this->o54_tipoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_tipoprograma"]:$this->o54_tipoprograma);
       $this->o54_estrategiaimp = ($this->o54_estrategiaimp == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_estrategiaimp"]:$this->o54_estrategiaimp);
     }else{
       $this->o54_anousu = ($this->o54_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_anousu"]:$this->o54_anousu);
       $this->o54_programa = ($this->o54_programa == ""?@$GLOBALS["HTTP_POST_VARS"]["o54_programa"]:$this->o54_programa);
     }
   }
   // funcao para inclusao
   function incluir ($o54_anousu,$o54_programa){ 
      $this->atualizacampos();
     if($this->o54_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "o54_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o54_codtri == null ){ 
       $this->erro_sql = " Campo Código tribunal nao Informado.";
       $this->erro_campo = "o54_codtri";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->o54_tipoprograma == null ){ 
       $this->erro_sql = " Campo Tipo de Programa nao Informado.";
       $this->erro_campo = "o54_tipoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->o54_anousu = $o54_anousu; 
       $this->o54_programa = $o54_programa; 
     if(($this->o54_anousu == null) || ($this->o54_anousu == "") ){ 
       $this->erro_sql = " Campo o54_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->o54_programa == null) || ($this->o54_programa == "") ){ 
       $this->erro_sql = " Campo o54_programa nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into orcprograma(
                                       o54_anousu 
                                      ,o54_programa 
                                      ,o54_descr 
                                      ,o54_codtri 
                                      ,o54_finali 
                                      ,o54_problema 
                                      ,o54_publicoalvo 
                                      ,o54_justificativa 
                                      ,o54_objsetorassociado 
                                      ,o54_tipoprograma 
                                      ,o54_estrategiaimp 
                       )
                values (
                                $this->o54_anousu 
                               ,$this->o54_programa 
                               ,'$this->o54_descr' 
                               ,'$this->o54_codtri' 
                               ,'$this->o54_finali' 
                               ,'$this->o54_problema' 
                               ,'$this->o54_publicoalvo' 
                               ,'$this->o54_justificativa' 
                               ,'$this->o54_objsetorassociado' 
                               ,$this->o54_tipoprograma 
                               ,'$this->o54_estrategiaimp' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programas Orçamento ($this->o54_anousu."-".$this->o54_programa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programas Orçamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programas Orçamento ($this->o54_anousu."-".$this->o54_programa) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o54_anousu."-".$this->o54_programa;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->o54_anousu,$this->o54_programa));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5260,'$this->o54_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,5261,'$this->o54_programa','I')");
       $resac = db_query("insert into db_acount values($acount,752,5260,'','".AddSlashes(pg_result($resaco,0,'o54_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,5261,'','".AddSlashes(pg_result($resaco,0,'o54_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,5262,'','".AddSlashes(pg_result($resaco,0,'o54_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,5263,'','".AddSlashes(pg_result($resaco,0,'o54_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,5264,'','".AddSlashes(pg_result($resaco,0,'o54_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,13646,'','".AddSlashes(pg_result($resaco,0,'o54_problema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,13647,'','".AddSlashes(pg_result($resaco,0,'o54_publicoalvo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,13648,'','".AddSlashes(pg_result($resaco,0,'o54_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,13649,'','".AddSlashes(pg_result($resaco,0,'o54_objsetorassociado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,13650,'','".AddSlashes(pg_result($resaco,0,'o54_tipoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,752,13651,'','".AddSlashes(pg_result($resaco,0,'o54_estrategiaimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($o54_anousu=null,$o54_programa=null) { 
      $this->atualizacampos();
     $sql = " update orcprograma set ";
     $virgula = "";
     if(trim($this->o54_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_anousu"])){ 
       $sql  .= $virgula." o54_anousu = $this->o54_anousu ";
       $virgula = ",";
       if(trim($this->o54_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "o54_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o54_programa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_programa"])){ 
       $sql  .= $virgula." o54_programa = $this->o54_programa ";
       $virgula = ",";
       if(trim($this->o54_programa) == null ){ 
         $this->erro_sql = " Campo Programa nao Informado.";
         $this->erro_campo = "o54_programa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o54_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_descr"])){ 
       $sql  .= $virgula." o54_descr = '$this->o54_descr' ";
       $virgula = ",";
       if(trim($this->o54_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "o54_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o54_codtri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_codtri"])){ 
       $sql  .= $virgula." o54_codtri = '$this->o54_codtri' ";
       $virgula = ",";
       if(trim($this->o54_codtri) == null ){ 
         $this->erro_sql = " Campo Código tribunal nao Informado.";
         $this->erro_campo = "o54_codtri";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o54_finali)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_finali"])){ 
       $sql  .= $virgula." o54_finali = '$this->o54_finali' ";
       $virgula = ",";
     }
     if(trim($this->o54_problema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_problema"])){ 
       $sql  .= $virgula." o54_problema = '$this->o54_problema' ";
       $virgula = ",";
     }
     if(trim($this->o54_publicoalvo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_publicoalvo"])){ 
       $sql  .= $virgula." o54_publicoalvo = '$this->o54_publicoalvo' ";
       $virgula = ",";
     }
     if(trim($this->o54_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_justificativa"])){ 
       $sql  .= $virgula." o54_justificativa = '$this->o54_justificativa' ";
       $virgula = ",";
     }
     if(trim($this->o54_objsetorassociado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_objsetorassociado"])){ 
       $sql  .= $virgula." o54_objsetorassociado = '$this->o54_objsetorassociado' ";
       $virgula = ",";
     }
     if(trim($this->o54_tipoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_tipoprograma"])){ 
       $sql  .= $virgula." o54_tipoprograma = $this->o54_tipoprograma ";
       $virgula = ",";
       if(trim($this->o54_tipoprograma) == null ){ 
         $this->erro_sql = " Campo Tipo de Programa nao Informado.";
         $this->erro_campo = "o54_tipoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->o54_estrategiaimp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["o54_estrategiaimp"])){ 
       $sql  .= $virgula." o54_estrategiaimp = '$this->o54_estrategiaimp' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($o54_anousu!=null){
       $sql .= " o54_anousu = $this->o54_anousu";
     }
     if($o54_programa!=null){
       $sql .= " and  o54_programa = $this->o54_programa";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->o54_anousu,$this->o54_programa));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5260,'$this->o54_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,5261,'$this->o54_programa','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_anousu"]) || $this->o54_anousu != "")
           $resac = db_query("insert into db_acount values($acount,752,5260,'".AddSlashes(pg_result($resaco,$conresaco,'o54_anousu'))."','$this->o54_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_programa"]) || $this->o54_programa != "")
           $resac = db_query("insert into db_acount values($acount,752,5261,'".AddSlashes(pg_result($resaco,$conresaco,'o54_programa'))."','$this->o54_programa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_descr"]) || $this->o54_descr != "")
           $resac = db_query("insert into db_acount values($acount,752,5262,'".AddSlashes(pg_result($resaco,$conresaco,'o54_descr'))."','$this->o54_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_codtri"]) || $this->o54_codtri != "")
           $resac = db_query("insert into db_acount values($acount,752,5263,'".AddSlashes(pg_result($resaco,$conresaco,'o54_codtri'))."','$this->o54_codtri',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_finali"]) || $this->o54_finali != "")
           $resac = db_query("insert into db_acount values($acount,752,5264,'".AddSlashes(pg_result($resaco,$conresaco,'o54_finali'))."','$this->o54_finali',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_problema"]) || $this->o54_problema != "")
           $resac = db_query("insert into db_acount values($acount,752,13646,'".AddSlashes(pg_result($resaco,$conresaco,'o54_problema'))."','$this->o54_problema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_publicoalvo"]) || $this->o54_publicoalvo != "")
           $resac = db_query("insert into db_acount values($acount,752,13647,'".AddSlashes(pg_result($resaco,$conresaco,'o54_publicoalvo'))."','$this->o54_publicoalvo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_justificativa"]) || $this->o54_justificativa != "")
           $resac = db_query("insert into db_acount values($acount,752,13648,'".AddSlashes(pg_result($resaco,$conresaco,'o54_justificativa'))."','$this->o54_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_objsetorassociado"]) || $this->o54_objsetorassociado != "")
           $resac = db_query("insert into db_acount values($acount,752,13649,'".AddSlashes(pg_result($resaco,$conresaco,'o54_objsetorassociado'))."','$this->o54_objsetorassociado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_tipoprograma"]) || $this->o54_tipoprograma != "")
           $resac = db_query("insert into db_acount values($acount,752,13650,'".AddSlashes(pg_result($resaco,$conresaco,'o54_tipoprograma'))."','$this->o54_tipoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["o54_estrategiaimp"]) || $this->o54_estrategiaimp != "")
           $resac = db_query("insert into db_acount values($acount,752,13651,'".AddSlashes(pg_result($resaco,$conresaco,'o54_estrategiaimp'))."','$this->o54_estrategiaimp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programas Orçamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->o54_anousu."-".$this->o54_programa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programas Orçamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->o54_anousu."-".$this->o54_programa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->o54_anousu."-".$this->o54_programa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($o54_anousu=null,$o54_programa=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($o54_anousu,$o54_programa));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5260,'$o54_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,5261,'$o54_programa','E')");
         $resac = db_query("insert into db_acount values($acount,752,5260,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,5261,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_programa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,5262,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,5263,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_codtri'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,5264,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_finali'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,13646,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_problema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,13647,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_publicoalvo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,13648,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,13649,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_objsetorassociado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,13650,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_tipoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,752,13651,'','".AddSlashes(pg_result($resaco,$iresaco,'o54_estrategiaimp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from orcprograma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($o54_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o54_anousu = $o54_anousu ";
        }
        if($o54_programa != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " o54_programa = $o54_programa ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programas Orçamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$o54_anousu."-".$o54_programa;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programas Orçamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$o54_anousu."-".$o54_programa;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$o54_anousu."-".$o54_programa;
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
        $this->erro_sql   = "Record Vazio na Tabela:orcprograma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $o54_anousu=null,$o54_programa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($o54_anousu!=null ){
         $sql2 .= " where orcprograma.o54_anousu = $o54_anousu "; 
       } 
       if($o54_programa!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprograma.o54_programa = $o54_programa "; 
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
   // funcao do sql 
   function sql_query_file ( $o54_anousu=null,$o54_programa=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from orcprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($o54_anousu!=null ){
         $sql2 .= " where orcprograma.o54_anousu = $o54_anousu "; 
       } 
       if($o54_programa!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " orcprograma.o54_programa = $o54_programa "; 
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
  
  function sql_query_buscaprogramasigfis ( $o54_anousu=null,$o54_programa=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= "      FROM orcprograma ";
    $sql .= " LEFT JOIN orcprogramaunidade       ON orcprogramaunidade.o14_anousu         = orcprograma.o54_anousu   ";
    $sql .= "                                   AND orcprogramaunidade.o14_orcprograma    = orcprograma.o54_programa ";
    $sql .= " LEFT JOIN orcprogramahorizontetemp ON orcprogramahorizontetemp.o17_anousu   = orcprograma.o54_anousu   ";
    $sql .= "                                   AND orcprogramahorizontetemp.o17_programa = orcprograma.o54_programa ";
    $sql2 = "";
    if($dbwhere==""){
      if($o54_anousu!=null ){
        $sql2 .= " where orcprograma.o54_anousu = $o54_anousu ";
      }
      if($o54_programa!=null ){
      if($sql2!=""){
      $sql2 .= " and ";
      }else{
      $sql2 .= " where ";
      }
        $sql2 .= " orcprograma.o54_programa = $o54_programa ";
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
  
  /**
   * 
   * Busca dados para gerar arquivo de Programa de Orçamento 
   * @param String $campos
   * @param String $campos2 // Campo Agregador
   * @param String $ordem
   * @param String $dbwhere
   * @param String $sGroupBy
   * @return string
   */
  function sql_query_programaOrcamento($campos="*", $campos2=null, $ordem=null, $dbwhere="", $sGroupBy="*" ) {
    $sql = "select ";
    if($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    if ($campos2 != null) {
      $sql .= $campos2;
    }
    
    $sql .= "  from orcprograma ";
    $sql .= " inner join orcdotacao on orcdotacao.o58_anousu   = orcprograma.o54_anousu ";
    $sql .= "											 and orcdotacao.o58_programa = orcprograma.o54_programa ";
    $sql2 = "";
    if($dbwhere != "") {
        $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ) {
      
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    $sql .= " group by ";
    if($campos != "*" ) {
      
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }    
        return $sql;
  }
  
}
?>