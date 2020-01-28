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

//MODULO: patrimonio
//CLASSE DA ENTIDADE benscorr
class cl_benscorr { 
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
   var $t63_codcor = 0; 
   var $t63_codbem = 0; 
   var $t63_valcor = 0; 
   var $t63_deprec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t63_codcor = int8 = Corre��o 
                 t63_codbem = int8 = C�digo do bem 
                 t63_valcor = float8 = Valor corrigido 
                 t63_deprec = float8 = Valor corrigido a menor 
                 ";
   //funcao construtor da classe 
   function cl_benscorr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("benscorr"); 
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
       $this->t63_codcor = ($this->t63_codcor == ""?@$GLOBALS["HTTP_POST_VARS"]["t63_codcor"]:$this->t63_codcor);
       $this->t63_codbem = ($this->t63_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t63_codbem"]:$this->t63_codbem);
       $this->t63_valcor = ($this->t63_valcor == ""?@$GLOBALS["HTTP_POST_VARS"]["t63_valcor"]:$this->t63_valcor);
       $this->t63_deprec = ($this->t63_deprec == ""?@$GLOBALS["HTTP_POST_VARS"]["t63_deprec"]:$this->t63_deprec);
     }else{
       $this->t63_codcor = ($this->t63_codcor == ""?@$GLOBALS["HTTP_POST_VARS"]["t63_codcor"]:$this->t63_codcor);
       $this->t63_codbem = ($this->t63_codbem == ""?@$GLOBALS["HTTP_POST_VARS"]["t63_codbem"]:$this->t63_codbem);
     }
   }
   // funcao para inclusao
   function incluir ($t63_codcor,$t63_codbem){ 
      $this->atualizacampos();
     if($this->t63_valcor == null ){ 
       $this->erro_sql = " Campo Valor corrigido nao Informado.";
       $this->erro_campo = "t63_valcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t63_deprec == null ){ 
       $this->erro_sql = " Campo Valor corrigido a menor nao Informado.";
       $this->erro_campo = "t63_deprec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->t63_codcor = $t63_codcor; 
       $this->t63_codbem = $t63_codbem; 
     if(($this->t63_codcor == null) || ($this->t63_codcor == "") ){ 
       $this->erro_sql = " Campo t63_codcor nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->t63_codbem == null) || ($this->t63_codbem == "") ){ 
       $this->erro_sql = " Campo t63_codbem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into benscorr(
                                       t63_codcor 
                                      ,t63_codbem 
                                      ,t63_valcor 
                                      ,t63_deprec 
                       )
                values (
                                $this->t63_codcor 
                               ,$this->t63_codbem 
                               ,$this->t63_valcor 
                               ,$this->t63_deprec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores das corre��es de bens ($this->t63_codcor."-".$this->t63_codbem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores das corre��es de bens j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores das corre��es de bens ($this->t63_codcor."-".$this->t63_codbem) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t63_codcor."-".$this->t63_codbem;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t63_codcor,$this->t63_codbem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5805,'$this->t63_codcor','I')");
       $resac = db_query("insert into db_acountkey values($acount,5806,'$this->t63_codbem','I')");
       $resac = db_query("insert into db_acount values($acount,923,5805,'','".AddSlashes(pg_result($resaco,0,'t63_codcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,923,5806,'','".AddSlashes(pg_result($resaco,0,'t63_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,923,5807,'','".AddSlashes(pg_result($resaco,0,'t63_valcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,923,5808,'','".AddSlashes(pg_result($resaco,0,'t63_deprec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t63_codcor=null,$t63_codbem=null) { 
      $this->atualizacampos();
     $sql = " update benscorr set ";
     $virgula = "";
     if(trim($this->t63_codcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t63_codcor"])){ 
       $sql  .= $virgula." t63_codcor = $this->t63_codcor ";
       $virgula = ",";
       if(trim($this->t63_codcor) == null ){ 
         $this->erro_sql = " Campo Corre��o nao Informado.";
         $this->erro_campo = "t63_codcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t63_codbem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t63_codbem"])){ 
       $sql  .= $virgula." t63_codbem = $this->t63_codbem ";
       $virgula = ",";
       if(trim($this->t63_codbem) == null ){ 
         $this->erro_sql = " Campo C�digo do bem nao Informado.";
         $this->erro_campo = "t63_codbem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t63_valcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t63_valcor"])){ 
       $sql  .= $virgula." t63_valcor = $this->t63_valcor ";
       $virgula = ",";
       if(trim($this->t63_valcor) == null ){ 
         $this->erro_sql = " Campo Valor corrigido nao Informado.";
         $this->erro_campo = "t63_valcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t63_deprec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t63_deprec"])){ 
       $sql  .= $virgula." t63_deprec = $this->t63_deprec ";
       $virgula = ",";
       if(trim($this->t63_deprec) == null ){ 
         $this->erro_sql = " Campo Valor corrigido a menor nao Informado.";
         $this->erro_campo = "t63_deprec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t63_codcor!=null){
       $sql .= " t63_codcor = $this->t63_codcor";
     }
     if($t63_codbem!=null){
       $sql .= " and  t63_codbem = $this->t63_codbem";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t63_codcor,$this->t63_codbem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5805,'$this->t63_codcor','A')");
         $resac = db_query("insert into db_acountkey values($acount,5806,'$this->t63_codbem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t63_codcor"]) || $this->t63_codcor != "")
           $resac = db_query("insert into db_acount values($acount,923,5805,'".AddSlashes(pg_result($resaco,$conresaco,'t63_codcor'))."','$this->t63_codcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t63_codbem"]) || $this->t63_codbem != "")
           $resac = db_query("insert into db_acount values($acount,923,5806,'".AddSlashes(pg_result($resaco,$conresaco,'t63_codbem'))."','$this->t63_codbem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t63_valcor"]) || $this->t63_valcor != "")
           $resac = db_query("insert into db_acount values($acount,923,5807,'".AddSlashes(pg_result($resaco,$conresaco,'t63_valcor'))."','$this->t63_valcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t63_deprec"]) || $this->t63_deprec != "")
           $resac = db_query("insert into db_acount values($acount,923,5808,'".AddSlashes(pg_result($resaco,$conresaco,'t63_deprec'))."','$this->t63_deprec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das corre��es de bens nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t63_codcor."-".$this->t63_codbem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das corre��es de bens nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t63_codcor."-".$this->t63_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t63_codcor."-".$this->t63_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t63_codcor=null,$t63_codbem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t63_codcor,$t63_codbem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5805,'$t63_codcor','E')");
         $resac = db_query("insert into db_acountkey values($acount,5806,'$t63_codbem','E')");
         $resac = db_query("insert into db_acount values($acount,923,5805,'','".AddSlashes(pg_result($resaco,$iresaco,'t63_codcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,923,5806,'','".AddSlashes(pg_result($resaco,$iresaco,'t63_codbem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,923,5807,'','".AddSlashes(pg_result($resaco,$iresaco,'t63_valcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,923,5808,'','".AddSlashes(pg_result($resaco,$iresaco,'t63_deprec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from benscorr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t63_codcor != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t63_codcor = $t63_codcor ";
        }
        if($t63_codbem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t63_codbem = $t63_codbem ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores das corre��es de bens nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t63_codcor."-".$t63_codbem;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores das corre��es de bens nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t63_codcor."-".$t63_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t63_codcor."-".$t63_codbem;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:benscorr";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t63_codcor=null,$t63_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscorr ";
     $sql .= "      inner join bens  on  bens.t52_bem = benscorr.t63_codbem";
     $sql .= "      inner join benscorlanc  on  benscorlanc.t62_codcor = benscorr.t63_codcor";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = bens.t52_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bens.t52_instit";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = bens.t52_depart";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = bens.t52_codcla";
     $sql .= "      inner join bensmarca  on  bensmarca.t65_sequencial = bens.t52_bensmarca";
     $sql .= "      inner join bensmodelo  on  bensmodelo.t66_sequencial = bens.t52_bensmodelo";
     $sql .= "      inner join bensmedida  on  bensmedida.t67_sequencial = bens.t52_bensmedida";
     $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = benscorlanc.t62_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($t63_codcor!=null ){
         $sql2 .= " where benscorr.t63_codcor = $t63_codcor "; 
       } 
       if($t63_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " benscorr.t63_codbem = $t63_codbem "; 
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
   function sql_query_file ( $t63_codcor=null,$t63_codbem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from benscorr ";
     $sql2 = "";
     if($dbwhere==""){
       if($t63_codcor!=null ){
         $sql2 .= " where benscorr.t63_codcor = $t63_codcor "; 
       } 
       if($t63_codbem!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " benscorr.t63_codbem = $t63_codbem "; 
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