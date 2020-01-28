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
//CLASSE DA ENTIDADE conrelvalor
class cl_conrelvalor { 
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
   var $c83_codigo = 0; 
   var $c83_instit = 0; 
   var $c83_informacao = null; 
   var $c83_anousu = 0; 
   var $c83_periodo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c83_codigo = int4 = codigo sequencial 
                 c83_instit = int4 = Instituição 
                 c83_informacao = char(30) = informação da variavel 
                 c83_anousu = int4 = Ano 
                 c83_periodo = char(2) = Periodo 
                 ";
   //funcao construtor da classe 
   function cl_conrelvalor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conrelvalor"); 
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
       $this->c83_codigo = ($this->c83_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_codigo"]:$this->c83_codigo);
       $this->c83_instit = ($this->c83_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_instit"]:$this->c83_instit);
       $this->c83_informacao = ($this->c83_informacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_informacao"]:$this->c83_informacao);
       $this->c83_anousu = ($this->c83_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_anousu"]:$this->c83_anousu);
       $this->c83_periodo = ($this->c83_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_periodo"]:$this->c83_periodo);
     }else{
       $this->c83_codigo = ($this->c83_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_codigo"]:$this->c83_codigo);
       $this->c83_instit = ($this->c83_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_instit"]:$this->c83_instit);
       $this->c83_periodo = ($this->c83_periodo == ""?@$GLOBALS["HTTP_POST_VARS"]["c83_periodo"]:$this->c83_periodo);
     }
   }
   // funcao para inclusao
   function incluir ($c83_codigo,$c83_instit,$c83_periodo){ 
      $this->atualizacampos();
     if($this->c83_informacao == null ){ 
       $this->erro_sql = " Campo informação da variavel nao Informado.";
       $this->erro_campo = "c83_informacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c83_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "c83_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->c83_codigo = $c83_codigo; 
       $this->c83_instit = $c83_instit; 
       $this->c83_periodo = $c83_periodo; 
     if(($this->c83_codigo == null) || ($this->c83_codigo == "") ){ 
       $this->erro_sql = " Campo c83_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c83_instit == null) || ($this->c83_instit == "") ){ 
       $this->erro_sql = " Campo c83_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->c83_periodo == null) || ($this->c83_periodo == "") ){ 
       $this->erro_sql = " Campo c83_periodo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conrelvalor(
                                       c83_codigo 
                                      ,c83_instit 
                                      ,c83_informacao 
                                      ,c83_anousu 
                                      ,c83_periodo 
                       )
                values (
                                $this->c83_codigo 
                               ,$this->c83_instit 
                               ,'$this->c83_informacao' 
                               ,$this->c83_anousu 
                               ,'$this->c83_periodo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "c83 ($this->c83_codigo."-".$this->c83_instit."-".$this->c83_periodo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "c83 já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "c83 ($this->c83_codigo."-".$this->c83_instit."-".$this->c83_periodo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo."-".$this->c83_instit."-".$this->c83_periodo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c83_codigo,$this->c83_instit,$this->c83_periodo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7264,'$this->c83_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,7414,'$this->c83_instit','I')");
       $resac = db_query("insert into db_acountkey values($acount,10723,'$this->c83_periodo','I')");
       $resac = db_query("insert into db_acount values($acount,1205,7264,'','".AddSlashes(pg_result($resaco,0,'c83_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1205,7414,'','".AddSlashes(pg_result($resaco,0,'c83_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1205,7268,'','".AddSlashes(pg_result($resaco,0,'c83_informacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1205,8640,'','".AddSlashes(pg_result($resaco,0,'c83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1205,10723,'','".AddSlashes(pg_result($resaco,0,'c83_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c83_codigo=null,$c83_instit=null,$c83_periodo=null) { 
      $this->atualizacampos();
     $sql = " update conrelvalor set ";
     $virgula = "";
     if(trim($this->c83_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_codigo"])){ 
       $sql  .= $virgula." c83_codigo = $this->c83_codigo ";
       $virgula = ",";
       if(trim($this->c83_codigo) == null ){ 
         $this->erro_sql = " Campo codigo sequencial nao Informado.";
         $this->erro_campo = "c83_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_instit"])){ 
       $sql  .= $virgula." c83_instit = $this->c83_instit ";
       $virgula = ",";
       if(trim($this->c83_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "c83_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_informacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_informacao"])){ 
       $sql  .= $virgula." c83_informacao = '$this->c83_informacao' ";
       $virgula = ",";
       if(trim($this->c83_informacao) == null ){ 
         $this->erro_sql = " Campo informação da variavel nao Informado.";
         $this->erro_campo = "c83_informacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_anousu"])){ 
       $sql  .= $virgula." c83_anousu = $this->c83_anousu ";
       $virgula = ",";
       if(trim($this->c83_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "c83_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c83_periodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c83_periodo"])){ 
       $sql  .= $virgula." c83_periodo = '$this->c83_periodo' ";
       $virgula = ",";
       if(trim($this->c83_periodo) == null ){ 
         $this->erro_sql = " Campo Periodo nao Informado.";
         $this->erro_campo = "c83_periodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c83_codigo!=null){
       $sql .= " c83_codigo = $this->c83_codigo";
     }
     if($c83_instit!=null){
       $sql .= " and  c83_instit = $this->c83_instit";
     }
     if($c83_periodo!=null){
       $sql .= " and  c83_periodo = '$this->c83_periodo'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c83_codigo,$this->c83_instit,$this->c83_periodo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7264,'$this->c83_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,7414,'$this->c83_instit','A')");
         $resac = db_query("insert into db_acountkey values($acount,10723,'$this->c83_periodo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1205,7264,'".AddSlashes(pg_result($resaco,$conresaco,'c83_codigo'))."','$this->c83_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_instit"]))
           $resac = db_query("insert into db_acount values($acount,1205,7414,'".AddSlashes(pg_result($resaco,$conresaco,'c83_instit'))."','$this->c83_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_informacao"]))
           $resac = db_query("insert into db_acount values($acount,1205,7268,'".AddSlashes(pg_result($resaco,$conresaco,'c83_informacao'))."','$this->c83_informacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1205,8640,'".AddSlashes(pg_result($resaco,$conresaco,'c83_anousu'))."','$this->c83_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c83_periodo"]))
           $resac = db_query("insert into db_acount values($acount,1205,10723,'".AddSlashes(pg_result($resaco,$conresaco,'c83_periodo'))."','$this->c83_periodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c83 nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo."-".$this->c83_instit."-".$this->c83_periodo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c83 nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo."-".$this->c83_instit."-".$this->c83_periodo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c83_codigo."-".$this->c83_instit."-".$this->c83_periodo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c83_codigo=null,$c83_instit=null,$c83_periodo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c83_codigo,$c83_instit,$c83_periodo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7264,'$c83_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,7414,'$c83_instit','E')");
         $resac = db_query("insert into db_acountkey values($acount,10723,'$c83_periodo','E')");
         $resac = db_query("insert into db_acount values($acount,1205,7264,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1205,7414,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1205,7268,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_informacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1205,8640,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1205,10723,'','".AddSlashes(pg_result($resaco,$iresaco,'c83_periodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conrelvalor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c83_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c83_codigo = $c83_codigo ";
        }
        if($c83_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c83_instit = $c83_instit ";
        }
        if($c83_periodo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c83_periodo = '$c83_periodo' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "c83 nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c83_codigo."-".$c83_instit."-".$c83_periodo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "c83 nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c83_codigo."-".$c83_instit."-".$c83_periodo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c83_codigo."-".$c83_instit."-".$c83_periodo;
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
        $this->erro_sql   = "Record Vazio na Tabela:conrelvalor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c83_codigo=null,$c83_instit=null,$c83_periodo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conrelvalor ";
     $sql .= "      inner join conrelinfo  on  conrelinfo.c83_codigo = conrelvalor.c83_codigo";
     $sql .= "      inner join orcparamrel  on  orcparamrel.o42_codparrel = conrelinfo.c83_codrel";
     $sql2 = "";
     if($dbwhere==""){
       if($c83_codigo!=null ){
         $sql2 .= " where conrelvalor.c83_codigo = $c83_codigo "; 
       } 
       if($c83_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conrelvalor.c83_instit = $c83_instit "; 
       } 
       if($c83_periodo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conrelvalor.c83_periodo = '$c83_periodo' "; 
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
   function sql_query_file ( $c83_codigo=null,$c83_instit=null,$c83_periodo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conrelvalor ";
     $sql2 = "";
     if($dbwhere==""){
       if($c83_codigo!=null ){
         $sql2 .= " where conrelvalor.c83_codigo = $c83_codigo "; 
       } 
       if($c83_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conrelvalor.c83_instit = $c83_instit "; 
       } 
       if($c83_periodo!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " conrelvalor.c83_periodo = '$c83_periodo' "; 
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