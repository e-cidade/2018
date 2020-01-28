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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucalclog
class cl_iptucalclog { 
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
   var $j27_codigo = 0; 
   var $j27_anousu = 0; 
   var $j27_data_dia = null; 
   var $j27_data_mes = null; 
   var $j27_data_ano = null; 
   var $j27_data = null; 
   var $j27_hora = null; 
   var $j27_usuario = 0; 
   var $j27_parcial = 'f'; 
   var $j27_quantaproc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j27_codigo = int4 = Sequencial 
                 j27_anousu = int4 = Ano 
                 j27_data = date = Data do calculo 
                 j27_hora = varchar(5) = Hora do calculo 
                 j27_usuario = int4 = Cod. Usuário 
                 j27_parcial = bool = Parcial 
                 j27_quantaproc = int4 = Quantidade de registros a processar 
                 ";
   //funcao construtor da classe 
   function cl_iptucalclog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalclog"); 
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
       $this->j27_codigo = ($this->j27_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_codigo"]:$this->j27_codigo);
       $this->j27_anousu = ($this->j27_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_anousu"]:$this->j27_anousu);
       if($this->j27_data == ""){
         $this->j27_data_dia = ($this->j27_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_data_dia"]:$this->j27_data_dia);
         $this->j27_data_mes = ($this->j27_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_data_mes"]:$this->j27_data_mes);
         $this->j27_data_ano = ($this->j27_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_data_ano"]:$this->j27_data_ano);
         if($this->j27_data_dia != ""){
            $this->j27_data = $this->j27_data_ano."-".$this->j27_data_mes."-".$this->j27_data_dia;
         }
       }
       $this->j27_hora = ($this->j27_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_hora"]:$this->j27_hora);
       $this->j27_usuario = ($this->j27_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_usuario"]:$this->j27_usuario);
       $this->j27_parcial = ($this->j27_parcial == "f"?@$GLOBALS["HTTP_POST_VARS"]["j27_parcial"]:$this->j27_parcial);
       $this->j27_quantaproc = ($this->j27_quantaproc == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_quantaproc"]:$this->j27_quantaproc);
     }else{
       $this->j27_codigo = ($this->j27_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j27_codigo"]:$this->j27_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j27_codigo){ 
      $this->atualizacampos();
     if($this->j27_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j27_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j27_data == null ){ 
       $this->erro_sql = " Campo Data do calculo nao Informado.";
       $this->erro_campo = "j27_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j27_hora == null ){ 
       $this->erro_sql = " Campo Hora do calculo nao Informado.";
       $this->erro_campo = "j27_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j27_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "j27_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j27_parcial == null ){ 
       $this->erro_sql = " Campo Parcial nao Informado.";
       $this->erro_campo = "j27_parcial";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j27_quantaproc == null ){ 
       $this->erro_sql = " Campo Quantidade de registros a processar nao Informado.";
       $this->erro_campo = "j27_quantaproc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j27_codigo == "" || $j27_codigo == null ){
       $result = db_query("select nextval('iptucalclog_j27_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptucalclog_j27_codigo_seq do campo: j27_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j27_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptucalclog_j27_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j27_codigo)){
         $this->erro_sql = " Campo j27_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j27_codigo = $j27_codigo; 
       }
     }
     if(($this->j27_codigo == null) || ($this->j27_codigo == "") ){ 
       $this->erro_sql = " Campo j27_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalclog(
                                       j27_codigo 
                                      ,j27_anousu 
                                      ,j27_data 
                                      ,j27_hora 
                                      ,j27_usuario 
                                      ,j27_parcial 
                                      ,j27_quantaproc 
                       )
                values (
                                $this->j27_codigo 
                               ,$this->j27_anousu 
                               ,".($this->j27_data == "null" || $this->j27_data == ""?"null":"'".$this->j27_data."'")." 
                               ,'$this->j27_hora' 
                               ,$this->j27_usuario 
                               ,'$this->j27_parcial' 
                               ,$this->j27_quantaproc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log do calculo do iptu ($this->j27_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log do calculo do iptu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log do calculo do iptu ($this->j27_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j27_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j27_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7887,'$this->j27_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1320,7887,'','".AddSlashes(pg_result($resaco,0,'j27_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1320,7883,'','".AddSlashes(pg_result($resaco,0,'j27_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1320,7885,'','".AddSlashes(pg_result($resaco,0,'j27_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1320,7895,'','".AddSlashes(pg_result($resaco,0,'j27_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1320,7884,'','".AddSlashes(pg_result($resaco,0,'j27_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1320,7894,'','".AddSlashes(pg_result($resaco,0,'j27_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1320,9738,'','".AddSlashes(pg_result($resaco,0,'j27_quantaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j27_codigo=null) { 
      $this->atualizacampos();
     $sql = " update iptucalclog set ";
     $virgula = "";
     if(trim($this->j27_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_codigo"])){ 
       $sql  .= $virgula." j27_codigo = $this->j27_codigo ";
       $virgula = ",";
       if(trim($this->j27_codigo) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j27_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j27_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_anousu"])){ 
       $sql  .= $virgula." j27_anousu = $this->j27_anousu ";
       $virgula = ",";
       if(trim($this->j27_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j27_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j27_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j27_data_dia"] !="") ){ 
       $sql  .= $virgula." j27_data = '$this->j27_data' ";
       $virgula = ",";
       if(trim($this->j27_data) == null ){ 
         $this->erro_sql = " Campo Data do calculo nao Informado.";
         $this->erro_campo = "j27_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j27_data_dia"])){ 
         $sql  .= $virgula." j27_data = null ";
         $virgula = ",";
         if(trim($this->j27_data) == null ){ 
           $this->erro_sql = " Campo Data do calculo nao Informado.";
           $this->erro_campo = "j27_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j27_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_hora"])){ 
       $sql  .= $virgula." j27_hora = '$this->j27_hora' ";
       $virgula = ",";
       if(trim($this->j27_hora) == null ){ 
         $this->erro_sql = " Campo Hora do calculo nao Informado.";
         $this->erro_campo = "j27_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j27_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_usuario"])){ 
       $sql  .= $virgula." j27_usuario = $this->j27_usuario ";
       $virgula = ",";
       if(trim($this->j27_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "j27_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j27_parcial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_parcial"])){ 
       $sql  .= $virgula." j27_parcial = '$this->j27_parcial' ";
       $virgula = ",";
       if(trim($this->j27_parcial) == null ){ 
         $this->erro_sql = " Campo Parcial nao Informado.";
         $this->erro_campo = "j27_parcial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j27_quantaproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j27_quantaproc"])){ 
       $sql  .= $virgula." j27_quantaproc = $this->j27_quantaproc ";
       $virgula = ",";
       if(trim($this->j27_quantaproc) == null ){ 
         $this->erro_sql = " Campo Quantidade de registros a processar nao Informado.";
         $this->erro_campo = "j27_quantaproc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j27_codigo!=null){
       $sql .= " j27_codigo = $this->j27_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j27_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7887,'$this->j27_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1320,7887,'".AddSlashes(pg_result($resaco,$conresaco,'j27_codigo'))."','$this->j27_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1320,7883,'".AddSlashes(pg_result($resaco,$conresaco,'j27_anousu'))."','$this->j27_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_data"]))
           $resac = db_query("insert into db_acount values($acount,1320,7885,'".AddSlashes(pg_result($resaco,$conresaco,'j27_data'))."','$this->j27_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_hora"]))
           $resac = db_query("insert into db_acount values($acount,1320,7895,'".AddSlashes(pg_result($resaco,$conresaco,'j27_hora'))."','$this->j27_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1320,7884,'".AddSlashes(pg_result($resaco,$conresaco,'j27_usuario'))."','$this->j27_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_parcial"]))
           $resac = db_query("insert into db_acount values($acount,1320,7894,'".AddSlashes(pg_result($resaco,$conresaco,'j27_parcial'))."','$this->j27_parcial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j27_quantaproc"]))
           $resac = db_query("insert into db_acount values($acount,1320,9738,'".AddSlashes(pg_result($resaco,$conresaco,'j27_quantaproc'))."','$this->j27_quantaproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do calculo do iptu nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j27_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do calculo do iptu nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j27_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j27_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j27_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j27_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7887,'$j27_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1320,7887,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1320,7883,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1320,7885,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1320,7895,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1320,7884,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1320,7894,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_parcial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1320,9738,'','".AddSlashes(pg_result($resaco,$iresaco,'j27_quantaproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucalclog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j27_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j27_codigo = $j27_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do calculo do iptu nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j27_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do calculo do iptu nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j27_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j27_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucalclog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalclog ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = iptucalclog.j27_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($j27_codigo!=null ){
         $sql2 .= " where iptucalclog.j27_codigo = $j27_codigo "; 
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
   function sql_query_file ( $j27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalclog ";
     $sql2 = "";
     if($dbwhere==""){
       if($j27_codigo!=null ){
         $sql2 .= " where iptucalclog.j27_codigo = $j27_codigo "; 
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
   function sql_query_inf ( $j27_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalclog ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = iptucalclog.j27_usuario";
     $sql .= "      inner join iptucalclogmat on iptucalclog.j27_codigo = iptucalclogmat.j28_codigo";
     $sql .= "      inner join iptubase on iptubase.j01_matric = iptucalclogmat.j28_matric";
     $sql .= "      inner join cgm on iptubase.j01_numcgm = cgm.z01_numcgm";
     $sql .= "      inner join iptucadlogcalc on iptucadlogcalc.j62_codigo = iptucalclogmat.j28_tipologcalc";
     $sql2 = "";
     if($dbwhere==""){
       if($j27_codigo!=null ){
         $sql2 .= " where iptucalclog.j27_codigo = $j27_codigo "; 
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