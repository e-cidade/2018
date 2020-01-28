<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE basesr
class cl_basesr { 
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
   var $r09_instit = 0; 
   var $r09_anousu = 0; 
   var $r09_mesusu = 0; 
   var $r09_base = null; 
   var $r09_rubric = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r09_instit = int4 = Cod. Instituição 
                 r09_anousu = int4 = Ano do Exercicio 
                 r09_mesusu = int4 = Mes do Exercicio 
                 r09_base = varchar(4) = Base 
                 r09_rubric = varchar(4) = Rubrica 
                 ";
   //funcao construtor da classe 
   function cl_basesr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("basesr"); 
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
       $this->r09_instit = ($this->r09_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_instit"]:$this->r09_instit);
       $this->r09_anousu = ($this->r09_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_anousu"]:$this->r09_anousu);
       $this->r09_mesusu = ($this->r09_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_mesusu"]:$this->r09_mesusu);
       $this->r09_base = ($this->r09_base == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_base"]:$this->r09_base);
       $this->r09_rubric = ($this->r09_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_rubric"]:$this->r09_rubric);
     }else{
       $this->r09_instit = ($this->r09_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_instit"]:$this->r09_instit);
       $this->r09_anousu = ($this->r09_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_anousu"]:$this->r09_anousu);
       $this->r09_mesusu = ($this->r09_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_mesusu"]:$this->r09_mesusu);
       $this->r09_base = ($this->r09_base == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_base"]:$this->r09_base);
       $this->r09_rubric = ($this->r09_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r09_rubric"]:$this->r09_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r09_anousu,$r09_mesusu,$r09_base,$r09_rubric,$r09_instit){ 
      $this->atualizacampos();
       $this->r09_anousu = $r09_anousu; 
       $this->r09_mesusu = $r09_mesusu; 
       $this->r09_base = $r09_base; 
       $this->r09_rubric = $r09_rubric; 
       $this->r09_instit = $r09_instit; 
     if(($this->r09_anousu == null) || ($this->r09_anousu == "") ){ 
       $this->erro_sql = " Campo r09_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r09_mesusu == null) || ($this->r09_mesusu == "") ){ 
       $this->erro_sql = " Campo r09_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r09_base == null) || ($this->r09_base == "") ){ 
       $this->erro_sql = " Campo r09_base nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r09_rubric == null) || ($this->r09_rubric == "") ){ 
       $this->erro_sql = " Campo r09_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r09_instit == null) || ($this->r09_instit == "") ){ 
       $this->erro_sql = " Campo r09_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into basesr(
                                       r09_instit 
                                      ,r09_anousu 
                                      ,r09_mesusu 
                                      ,r09_base 
                                      ,r09_rubric 
                       )
                values (
                                $this->r09_instit 
                               ,$this->r09_anousu 
                               ,$this->r09_mesusu 
                               ,'$this->r09_base' 
                               ,'$this->r09_rubric' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Interliga o arquivo bases com as rubricas          ($this->r09_anousu."-".$this->r09_mesusu."-".$this->r09_base."-".$this->r09_rubric."-".$this->r09_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Interliga o arquivo bases com as rubricas          já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Interliga o arquivo bases com as rubricas          ($this->r09_anousu."-".$this->r09_mesusu."-".$this->r09_base."-".$this->r09_rubric."-".$this->r09_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r09_anousu."-".$this->r09_mesusu."-".$this->r09_base."-".$this->r09_rubric."-".$this->r09_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r09_anousu,$this->r09_mesusu,$this->r09_base,$this->r09_rubric,$this->r09_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3693,'$this->r09_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3694,'$this->r09_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3695,'$this->r09_base','I')");
       $resac = db_query("insert into db_acountkey values($acount,3696,'$this->r09_rubric','I')");
       $resac = db_query("insert into db_acountkey values($acount,9891,'$this->r09_instit','I')");
       $resac = db_query("insert into db_acount values($acount,531,9891,'','".AddSlashes(pg_result($resaco,0,'r09_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,531,3693,'','".AddSlashes(pg_result($resaco,0,'r09_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,531,3694,'','".AddSlashes(pg_result($resaco,0,'r09_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,531,3695,'','".AddSlashes(pg_result($resaco,0,'r09_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,531,3696,'','".AddSlashes(pg_result($resaco,0,'r09_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r09_anousu=null,$r09_mesusu=null,$r09_base=null,$r09_rubric=null,$r09_instit=null) { 
      $this->atualizacampos();
     $sql = " update basesr set ";
     $virgula = "";
     if(trim($this->r09_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r09_instit"])){ 
       $sql  .= $virgula." r09_instit = $this->r09_instit ";
       $virgula = ",";
       if(trim($this->r09_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "r09_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r09_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r09_anousu"])){ 
       $sql  .= $virgula." r09_anousu = $this->r09_anousu ";
       $virgula = ",";
       if(trim($this->r09_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r09_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r09_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r09_mesusu"])){ 
       $sql  .= $virgula." r09_mesusu = $this->r09_mesusu ";
       $virgula = ",";
       if(trim($this->r09_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r09_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r09_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r09_base"])){ 
       $sql  .= $virgula." r09_base = '$this->r09_base' ";
       $virgula = ",";
       if(trim($this->r09_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "r09_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r09_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r09_rubric"])){ 
       $sql  .= $virgula." r09_rubric = '$this->r09_rubric' ";
       $virgula = ",";
       if(trim($this->r09_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r09_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r09_anousu!=null){
       $sql .= " r09_anousu = $this->r09_anousu";
     }
     if($r09_mesusu!=null){
       $sql .= " and  r09_mesusu = $this->r09_mesusu";
     }
     if($r09_base!=null){
       $sql .= " and  r09_base = '$this->r09_base'";
     }
     if($r09_rubric!=null){
       $sql .= " and  r09_rubric = '$this->r09_rubric'";
     }
     if($r09_instit!=null){
       $sql .= " and  r09_instit = $this->r09_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r09_anousu,$this->r09_mesusu,$this->r09_base,$this->r09_rubric,$this->r09_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3693,'$this->r09_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3694,'$this->r09_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3695,'$this->r09_base','A')");
         $resac = db_query("insert into db_acountkey values($acount,3696,'$this->r09_rubric','A')");
         $resac = db_query("insert into db_acountkey values($acount,9891,'$this->r09_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r09_instit"]))
           $resac = db_query("insert into db_acount values($acount,531,9891,'".AddSlashes(pg_result($resaco,$conresaco,'r09_instit'))."','$this->r09_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r09_anousu"]))
           $resac = db_query("insert into db_acount values($acount,531,3693,'".AddSlashes(pg_result($resaco,$conresaco,'r09_anousu'))."','$this->r09_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r09_mesusu"]))
           $resac = db_query("insert into db_acount values($acount,531,3694,'".AddSlashes(pg_result($resaco,$conresaco,'r09_mesusu'))."','$this->r09_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r09_base"]))
           $resac = db_query("insert into db_acount values($acount,531,3695,'".AddSlashes(pg_result($resaco,$conresaco,'r09_base'))."','$this->r09_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r09_rubric"]))
           $resac = db_query("insert into db_acount values($acount,531,3696,'".AddSlashes(pg_result($resaco,$conresaco,'r09_rubric'))."','$this->r09_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Interliga o arquivo bases com as rubricas          nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r09_anousu."-".$this->r09_mesusu."-".$this->r09_base."-".$this->r09_rubric."-".$this->r09_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Interliga o arquivo bases com as rubricas          nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r09_anousu."-".$this->r09_mesusu."-".$this->r09_base."-".$this->r09_rubric."-".$this->r09_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r09_anousu."-".$this->r09_mesusu."-".$this->r09_base."-".$this->r09_rubric."-".$this->r09_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r09_anousu=null,$r09_mesusu=null,$r09_base=null,$r09_rubric=null,$r09_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r09_anousu,$r09_mesusu,$r09_base,$r09_rubric,$r09_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3693,'$r09_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3694,'$r09_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3695,'$r09_base','E')");
         $resac = db_query("insert into db_acountkey values($acount,3696,'$r09_rubric','E')");
         $resac = db_query("insert into db_acountkey values($acount,9891,'$r09_instit','E')");
         $resac = db_query("insert into db_acount values($acount,531,9891,'','".AddSlashes(pg_result($resaco,$iresaco,'r09_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,531,3693,'','".AddSlashes(pg_result($resaco,$iresaco,'r09_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,531,3694,'','".AddSlashes(pg_result($resaco,$iresaco,'r09_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,531,3695,'','".AddSlashes(pg_result($resaco,$iresaco,'r09_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,531,3696,'','".AddSlashes(pg_result($resaco,$iresaco,'r09_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from basesr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r09_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r09_anousu = $r09_anousu ";
        }
        if($r09_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r09_mesusu = $r09_mesusu ";
        }
        if($r09_base != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r09_base = '$r09_base' ";
        }
        if($r09_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r09_rubric = '$r09_rubric' ";
        }
        if($r09_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r09_instit = $r09_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Interliga o arquivo bases com as rubricas          nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r09_anousu."-".$r09_mesusu."-".$r09_base."-".$r09_rubric."-".$r09_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Interliga o arquivo bases com as rubricas          nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r09_anousu."-".$r09_mesusu."-".$r09_base."-".$r09_rubric."-".$r09_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r09_anousu."-".$r09_mesusu."-".$r09_base."-".$r09_rubric."-".$r09_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:basesr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function atualiza_incluir (){
  	 $this->incluir($this->r09_anousu,$this->r09_mesusu,$this->r09_base,$this->r09_rubric);
   }
   function sql_query ( $r09_anousu=null,$r09_mesusu=null,$r09_base=null,$r09_rubric=null,$r09_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from basesr ";
     $sql .= "      inner join bases  on  bases.r08_anousu = basesr.r09_anousu 
		                                 and  bases.r08_mesusu = basesr.r09_mesusu 
																		 and  bases.r08_codigo = basesr.r09_base 
																		 and  bases.r08_instit = basesr.r09_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = bases.r08_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($r09_anousu!=null ){
         $sql2 .= " where basesr.r09_anousu = $r09_anousu "; 
       } 
       if($r09_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_mesusu = $r09_mesusu "; 
       } 
       if($r09_base!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_base = '$r09_base' "; 
       } 
       if($r09_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_rubric = '$r09_rubric' "; 
       } 
       if($r09_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_instit = $r09_instit "; 
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
   function sql_query_file ( $r09_anousu=null,$r09_mesusu=null,$r09_base=null,$r09_rubric=null,$r09_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from basesr ";
     $sql2 = "";
     if($dbwhere==""){
       if($r09_anousu!=null ){
         $sql2 .= " where basesr.r09_anousu = $r09_anousu "; 
       } 
       if($r09_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_mesusu = $r09_mesusu "; 
       } 
       if($r09_base!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_base = '$r09_base' "; 
       } 
       if($r09_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_rubric = '$r09_rubric' "; 
       } 
       if($r09_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_instit = $r09_instit "; 
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

  function sql_rubricas_base ( $r09_anousu=null,$r09_mesusu=null,$r09_base=null,$r09_rubric=null,$r09_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from basesr ";
     $sql .= " inner join rhrubricas on rh27_rubric = r09_rubric";
     $sql2 = "";
     if($dbwhere==""){
       if($r09_anousu!=null ){
         $sql2 .= " where basesr.r09_anousu = $r09_anousu "; 
       } 
       if($r09_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_mesusu = $r09_mesusu "; 
       } 
       if($r09_base!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_base = '$r09_base' "; 
       } 
       if($r09_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_rubric = '$r09_rubric' "; 
       } 
       if($r09_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " basesr.r09_instit = $r09_instit "; 
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