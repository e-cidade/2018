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

//MODULO: contrib
//CLASSE DA ENTIDADE projmelhoriasmatric
class cl_projmelhoriasmatric { 
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
   var $d41_codigo = 0; 
   var $d41_matric = 0; 
   var $d41_testada = 0; 
   var $d41_eixo = 0; 
   var $d41_obs = null; 
   var $d41_auto = 'f'; 
   var $d41_pgtopref = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 d41_codigo = int4 = Código da lista de projeto 
                 d41_matric = int4 = Matrícula 
                 d41_testada = float8 = Testada Ml 
                 d41_eixo = float8 = Eixo 
                 d41_obs = varchar(200) = Observações 
                 d41_auto = bool = Automático 
                 d41_pgtopref = bool = Pgto prefeitura 
                 ";
   //funcao construtor da classe 
   function cl_projmelhoriasmatric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("projmelhoriasmatric"); 
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
       $this->d41_codigo = ($this->d41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_codigo"]:$this->d41_codigo);
       $this->d41_matric = ($this->d41_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_matric"]:$this->d41_matric);
       $this->d41_testada = ($this->d41_testada == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_testada"]:$this->d41_testada);
       $this->d41_eixo = ($this->d41_eixo == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_eixo"]:$this->d41_eixo);
       $this->d41_obs = ($this->d41_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_obs"]:$this->d41_obs);
       $this->d41_auto = ($this->d41_auto == "f"?@$GLOBALS["HTTP_POST_VARS"]["d41_auto"]:$this->d41_auto);
       $this->d41_pgtopref = ($this->d41_pgtopref == "f"?@$GLOBALS["HTTP_POST_VARS"]["d41_pgtopref"]:$this->d41_pgtopref);
     }else{
       $this->d41_codigo = ($this->d41_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_codigo"]:$this->d41_codigo);
       $this->d41_matric = ($this->d41_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["d41_matric"]:$this->d41_matric);
     }
   }
   // funcao para inclusao
   function incluir ($d41_codigo,$d41_matric){ 
      $this->atualizacampos();
     if($this->d41_testada == null ){ 
       $this->erro_sql = " Campo Testada Ml nao Informado.";
       $this->erro_campo = "d41_testada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d41_eixo == null ){ 
       $this->d41_eixo = "0";
     }
     if($this->d41_auto == null ){ 
       $this->erro_sql = " Campo Automático nao Informado.";
       $this->erro_campo = "d41_auto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->d41_pgtopref == null ){ 
       $this->erro_sql = " Campo Pgto prefeitura nao Informado.";
       $this->erro_campo = "d41_pgtopref";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->d41_codigo = $d41_codigo; 
       $this->d41_matric = $d41_matric; 
     if(($this->d41_codigo == null) || ($this->d41_codigo == "") ){ 
       $this->erro_sql = " Campo d41_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->d41_matric == null) || ($this->d41_matric == "") ){ 
       $this->erro_sql = " Campo d41_matric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into projmelhoriasmatric(
                                       d41_codigo 
                                      ,d41_matric 
                                      ,d41_testada 
                                      ,d41_eixo 
                                      ,d41_obs 
                                      ,d41_auto 
                                      ,d41_pgtopref 
                       )
                values (
                                $this->d41_codigo 
                               ,$this->d41_matric 
                               ,$this->d41_testada 
                               ,$this->d41_eixo 
                               ,'$this->d41_obs' 
                               ,'$this->d41_auto' 
                               ,'$this->d41_pgtopref' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matrículas da lista de projeto ($this->d41_codigo."-".$this->d41_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matrículas da lista de projeto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matrículas da lista de projeto ($this->d41_codigo."-".$this->d41_matric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d41_codigo."-".$this->d41_matric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->d41_codigo,$this->d41_matric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3577,'$this->d41_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,3578,'$this->d41_matric','I')");
       $resac = db_query("insert into db_acount values($acount,514,3577,'','".AddSlashes(pg_result($resaco,0,'d41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,514,3578,'','".AddSlashes(pg_result($resaco,0,'d41_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,514,3579,'','".AddSlashes(pg_result($resaco,0,'d41_testada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,514,3580,'','".AddSlashes(pg_result($resaco,0,'d41_eixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,514,3581,'','".AddSlashes(pg_result($resaco,0,'d41_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,514,3582,'','".AddSlashes(pg_result($resaco,0,'d41_auto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,514,4857,'','".AddSlashes(pg_result($resaco,0,'d41_pgtopref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($d41_codigo=null,$d41_matric=null) { 
      $this->atualizacampos();
     $sql = " update projmelhoriasmatric set ";
     $virgula = "";
     if(trim($this->d41_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_codigo"])){ 
       $sql  .= $virgula." d41_codigo = $this->d41_codigo ";
       $virgula = ",";
       if(trim($this->d41_codigo) == null ){ 
         $this->erro_sql = " Campo Código da lista de projeto nao Informado.";
         $this->erro_campo = "d41_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d41_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_matric"])){ 
       $sql  .= $virgula." d41_matric = $this->d41_matric ";
       $virgula = ",";
       if(trim($this->d41_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "d41_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d41_testada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_testada"])){ 
       $sql  .= $virgula." d41_testada = $this->d41_testada ";
       $virgula = ",";
       if(trim($this->d41_testada) == null ){ 
         $this->erro_sql = " Campo Testada Ml nao Informado.";
         $this->erro_campo = "d41_testada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d41_eixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_eixo"])){ 
        if(trim($this->d41_eixo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["d41_eixo"])){ 
           $this->d41_eixo = "0" ; 
        } 
       $sql  .= $virgula." d41_eixo = $this->d41_eixo ";
       $virgula = ",";
     }
     if(trim($this->d41_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_obs"])){ 
       $sql  .= $virgula." d41_obs = '$this->d41_obs' ";
       $virgula = ",";
     }
     if(trim($this->d41_auto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_auto"])){ 
       $sql  .= $virgula." d41_auto = '$this->d41_auto' ";
       $virgula = ",";
       if(trim($this->d41_auto) == null ){ 
         $this->erro_sql = " Campo Automático nao Informado.";
         $this->erro_campo = "d41_auto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->d41_pgtopref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["d41_pgtopref"])){ 
       $sql  .= $virgula." d41_pgtopref = '$this->d41_pgtopref' ";
       $virgula = ",";
       if(trim($this->d41_pgtopref) == null ){ 
         $this->erro_sql = " Campo Pgto prefeitura nao Informado.";
         $this->erro_campo = "d41_pgtopref";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($d41_codigo!=null){
       $sql .= " d41_codigo = $this->d41_codigo";
     }
     if($d41_matric!=null){
       $sql .= " and  d41_matric = $this->d41_matric";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->d41_codigo,$this->d41_matric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3577,'$this->d41_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,3578,'$this->d41_matric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_codigo"]))
           $resac = db_query("insert into db_acount values($acount,514,3577,'".AddSlashes(pg_result($resaco,$conresaco,'d41_codigo'))."','$this->d41_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_matric"]))
           $resac = db_query("insert into db_acount values($acount,514,3578,'".AddSlashes(pg_result($resaco,$conresaco,'d41_matric'))."','$this->d41_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_testada"]))
           $resac = db_query("insert into db_acount values($acount,514,3579,'".AddSlashes(pg_result($resaco,$conresaco,'d41_testada'))."','$this->d41_testada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_eixo"]))
           $resac = db_query("insert into db_acount values($acount,514,3580,'".AddSlashes(pg_result($resaco,$conresaco,'d41_eixo'))."','$this->d41_eixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_obs"]))
           $resac = db_query("insert into db_acount values($acount,514,3581,'".AddSlashes(pg_result($resaco,$conresaco,'d41_obs'))."','$this->d41_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_auto"]))
           $resac = db_query("insert into db_acount values($acount,514,3582,'".AddSlashes(pg_result($resaco,$conresaco,'d41_auto'))."','$this->d41_auto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["d41_pgtopref"]))
           $resac = db_query("insert into db_acount values($acount,514,4857,'".AddSlashes(pg_result($resaco,$conresaco,'d41_pgtopref'))."','$this->d41_pgtopref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas da lista de projeto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->d41_codigo."-".$this->d41_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas da lista de projeto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->d41_codigo."-".$this->d41_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->d41_codigo."-".$this->d41_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($d41_codigo=null,$d41_matric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($d41_codigo,$d41_matric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3577,'$d41_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,3578,'$d41_matric','E')");
         $resac = db_query("insert into db_acount values($acount,514,3577,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,514,3578,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,514,3579,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_testada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,514,3580,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_eixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,514,3581,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,514,3582,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_auto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,514,4857,'','".AddSlashes(pg_result($resaco,$iresaco,'d41_pgtopref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from projmelhoriasmatric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($d41_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d41_codigo = $d41_codigo ";
        }
        if($d41_matric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " d41_matric = $d41_matric ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas da lista de projeto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$d41_codigo."-".$d41_matric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas da lista de projeto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$d41_codigo."-".$d41_matric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$d41_codigo."-".$d41_matric;
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
        $this->erro_sql   = "Record Vazio na Tabela:projmelhoriasmatric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $d41_codigo=null,$d41_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from projmelhoriasmatric ";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = projmelhoriasmatric.d41_matric";
     $sql .= "      inner join projmelhorias  on  projmelhorias.d40_codigo = projmelhoriasmatric.d41_codigo";
     $sql .= "      inner join lote  on  lote.j34_idbql = iptubase.j01_idbql";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = iptubase.j01_numcgm";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = projmelhorias.d40_codlog";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = projmelhorias.d40_login";
     $sql2 = "";
     if($dbwhere==""){
       if($d41_codigo!=null ){
         $sql2 .= " where projmelhoriasmatric.d41_codigo = $d41_codigo "; 
       } 
       if($d41_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " projmelhoriasmatric.d41_matric = $d41_matric "; 
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
   function sql_query_file ( $d41_codigo=null,$d41_matric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from projmelhoriasmatric ";
     $sql2 = "";
     if($dbwhere==""){
       if($d41_codigo!=null ){
         $sql2 .= " where projmelhoriasmatric.d41_codigo = $d41_codigo "; 
       } 
       if($d41_matric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " projmelhoriasmatric.d41_matric = $d41_matric "; 
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