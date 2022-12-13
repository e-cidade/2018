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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoexerecurso
class cl_conplanoexerecurso { 
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
   var $c89_anousu = 0; 
   var $c89_recurso = 0; 
   var $c89_reduz = 0; 
   var $c89_vlrcre = 0; 
   var $c89_vlrdeb = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c89_anousu = int4 = Exercício 
                 c89_recurso = int4 = Codigo do Recurso 
                 c89_reduz = int4 = Reduzido 
                 c89_vlrcre = float8 = Saldo Abertura a Credito 
                 c89_vlrdeb = float8 = Saldo Abertura a Débito 
                 ";
   //funcao construtor da classe 
   function cl_conplanoexerecurso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoexerecurso"); 
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
       $this->c89_anousu = ($this->c89_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_anousu"]:$this->c89_anousu);
       $this->c89_recurso = ($this->c89_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_recurso"]:$this->c89_recurso);
       $this->c89_reduz = ($this->c89_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_reduz"]:$this->c89_reduz);
       $this->c89_vlrcre = ($this->c89_vlrcre == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_vlrcre"]:$this->c89_vlrcre);
       $this->c89_vlrdeb = ($this->c89_vlrdeb == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_vlrdeb"]:$this->c89_vlrdeb);
     }else{
       $this->c89_anousu = ($this->c89_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_anousu"]:$this->c89_anousu);
       $this->c89_recurso = ($this->c89_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_recurso"]:$this->c89_recurso);
       $this->c89_reduz = ($this->c89_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c89_reduz"]:$this->c89_reduz);
     }
   }
   // funcao para inclusao
   function incluir ($c89_anousu,$c89_recurso,$c89_reduz){ 
      $this->atualizacampos();
     if($this->c89_vlrcre == null ){ 
       $this->erro_sql = " Campo Saldo Abertura a Credito nao Informado.";
       $this->erro_campo = "c89_vlrcre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c89_vlrdeb == null ){ 
       $this->erro_sql = " Campo Saldo Abertura a Débito nao Informado.";
       $this->erro_campo = "c89_vlrdeb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c89_anousu = $c89_anousu; 
       $this->c89_recurso = $c89_recurso; 
       $this->c89_reduz = $c89_reduz; 
     if(($this->c89_anousu == null) || ($this->c89_anousu == "") ){ 
       $this->erro_sql = " Campo c89_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c89_recurso == null) || ($this->c89_recurso == "") ){ 
       $this->erro_sql = " Campo c89_recurso nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c89_reduz == null) || ($this->c89_reduz == "") ){ 
       $this->erro_sql = " Campo c89_reduz nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoexerecurso(
                                       c89_anousu 
                                      ,c89_recurso 
                                      ,c89_reduz 
                                      ,c89_vlrcre 
                                      ,c89_vlrdeb 
                       )
                values (
                                $this->c89_anousu 
                               ,$this->c89_recurso 
                               ,$this->c89_reduz 
                               ,$this->c89_vlrcre 
                               ,$this->c89_vlrdeb 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->c89_anousu."-".$this->c89_recurso."-".$this->c89_reduz) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->c89_anousu."-".$this->c89_recurso."-".$this->c89_reduz) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c89_anousu."-".$this->c89_recurso."-".$this->c89_reduz;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c89_anousu,$this->c89_recurso,$this->c89_reduz));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9817,'$this->c89_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,9818,'$this->c89_recurso','I')");
       $resac = db_query("insert into db_acountkey values($acount,9819,'$this->c89_reduz','I')");
       $resac = db_query("insert into db_acount values($acount,1686,9817,'','".AddSlashes(pg_result($resaco,0,'c89_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1686,9818,'','".AddSlashes(pg_result($resaco,0,'c89_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1686,9819,'','".AddSlashes(pg_result($resaco,0,'c89_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1686,9821,'','".AddSlashes(pg_result($resaco,0,'c89_vlrcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1686,9820,'','".AddSlashes(pg_result($resaco,0,'c89_vlrdeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c89_anousu=null,$c89_recurso=null,$c89_reduz=null) { 
      $this->atualizacampos();
     $sql = " update conplanoexerecurso set ";
     $virgula = "";
     if(trim($this->c89_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c89_anousu"])){ 
       $sql  .= $virgula." c89_anousu = $this->c89_anousu ";
       $virgula = ",";
       if(trim($this->c89_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "c89_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c89_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c89_recurso"])){ 
       $sql  .= $virgula." c89_recurso = $this->c89_recurso ";
       $virgula = ",";
       if(trim($this->c89_recurso) == null ){ 
         $this->erro_sql = " Campo Codigo do Recurso nao Informado.";
         $this->erro_campo = "c89_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c89_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c89_reduz"])){ 
       $sql  .= $virgula." c89_reduz = $this->c89_reduz ";
       $virgula = ",";
       if(trim($this->c89_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c89_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c89_vlrcre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c89_vlrcre"])){ 
       $sql  .= $virgula." c89_vlrcre = $this->c89_vlrcre ";
       $virgula = ",";
       if(trim($this->c89_vlrcre) == null ){ 
         $this->erro_sql = " Campo Saldo Abertura a Credito nao Informado.";
         $this->erro_campo = "c89_vlrcre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c89_vlrdeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c89_vlrdeb"])){ 
       $sql  .= $virgula." c89_vlrdeb = $this->c89_vlrdeb ";
       $virgula = ",";
       if(trim($this->c89_vlrdeb) == null ){ 
         $this->erro_sql = " Campo Saldo Abertura a Débito nao Informado.";
         $this->erro_campo = "c89_vlrdeb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c89_anousu!=null){
       $sql .= " c89_anousu = $this->c89_anousu";
     }
     if($c89_recurso!=null){
       $sql .= " and  c89_recurso = $this->c89_recurso";
     }
     if($c89_reduz!=null){
       $sql .= " and  c89_reduz = $this->c89_reduz";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c89_anousu,$this->c89_recurso,$this->c89_reduz));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9817,'$this->c89_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,9818,'$this->c89_recurso','A')");
         $resac = db_query("insert into db_acountkey values($acount,9819,'$this->c89_reduz','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c89_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1686,9817,'".AddSlashes(pg_result($resaco,$conresaco,'c89_anousu'))."','$this->c89_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c89_recurso"]))
           $resac = db_query("insert into db_acount values($acount,1686,9818,'".AddSlashes(pg_result($resaco,$conresaco,'c89_recurso'))."','$this->c89_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c89_reduz"]))
           $resac = db_query("insert into db_acount values($acount,1686,9819,'".AddSlashes(pg_result($resaco,$conresaco,'c89_reduz'))."','$this->c89_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c89_vlrcre"]))
           $resac = db_query("insert into db_acount values($acount,1686,9821,'".AddSlashes(pg_result($resaco,$conresaco,'c89_vlrcre'))."','$this->c89_vlrcre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c89_vlrdeb"]))
           $resac = db_query("insert into db_acount values($acount,1686,9820,'".AddSlashes(pg_result($resaco,$conresaco,'c89_vlrdeb'))."','$this->c89_vlrdeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c89_anousu."-".$this->c89_recurso."-".$this->c89_reduz;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c89_anousu."-".$this->c89_recurso."-".$this->c89_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c89_anousu."-".$this->c89_recurso."-".$this->c89_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c89_anousu=null,$c89_recurso=null,$c89_reduz=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c89_anousu,$c89_recurso,$c89_reduz));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9817,'$c89_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,9818,'$c89_recurso','E')");
         $resac = db_query("insert into db_acountkey values($acount,9819,'$c89_reduz','E')");
         $resac = db_query("insert into db_acount values($acount,1686,9817,'','".AddSlashes(pg_result($resaco,$iresaco,'c89_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1686,9818,'','".AddSlashes(pg_result($resaco,$iresaco,'c89_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1686,9819,'','".AddSlashes(pg_result($resaco,$iresaco,'c89_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1686,9821,'','".AddSlashes(pg_result($resaco,$iresaco,'c89_vlrcre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1686,9820,'','".AddSlashes(pg_result($resaco,$iresaco,'c89_vlrdeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoexerecurso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c89_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c89_anousu = $c89_anousu ";
        }
        if($c89_recurso != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c89_recurso = $c89_recurso ";
        }
        if($c89_reduz != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c89_reduz = $c89_reduz ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c89_anousu."-".$c89_recurso."-".$c89_reduz;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c89_anousu."-".$c89_recurso."-".$c89_reduz;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c89_anousu."-".$c89_recurso."-".$c89_reduz;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoexerecurso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c89_anousu=null,$c89_recurso=null,$c89_reduz=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoexerecurso ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoexerecurso.c89_recurso";
     $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_anousu = conplanoexerecurso.c89_anousu and  conplanoreduz.c61_reduz = conplanoexerecurso.c89_reduz";
     $sql .= "      inner join db_config  on  db_config.codigo = conplanoreduz.c61_instit";
     $sql .= "      inner join conplano   on  c60_codcon = conplanoreduz.c61_codcon and   c60_anousu = conplanoreduz.c61_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($c89_anousu!=null ){
         $sql2 .= " where conplanoexerecurso.c89_anousu = $c89_anousu "; 
       } 
       if($c89_recurso!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoexerecurso.c89_recurso = $c89_recurso "; 
       } 
       if($c89_reduz!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoexerecurso.c89_reduz = $c89_reduz "; 
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
   function sql_query_file ( $c89_anousu=null,$c89_recurso=null,$c89_reduz=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoexerecurso ";
     $sql2 = "";
     if($dbwhere==""){
       if($c89_anousu!=null ){
         $sql2 .= " where conplanoexerecurso.c89_anousu = $c89_anousu "; 
       } 
       if($c89_recurso!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoexerecurso.c89_recurso = $c89_recurso "; 
       } 
       if($c89_reduz!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conplanoexerecurso.c89_reduz = $c89_reduz "; 
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
}
?>