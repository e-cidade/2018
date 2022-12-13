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

//MODULO: TFD
//CLASSE DA ENTIDADE tfd_destino
class cl_tfd_destino { 
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
   var $tf03_i_codigo = 0; 
   var $tf03_c_descr = null; 
   var $tf03_i_tipodistancia = 0; 
   var $tf03_f_distancia = 0; 
   var $tf03_d_validadeini_dia = null; 
   var $tf03_d_validadeini_mes = null; 
   var $tf03_d_validadeini_ano = null; 
   var $tf03_d_validadeini = null; 
   var $tf03_d_validadefim_dia = null; 
   var $tf03_d_validadefim_mes = null; 
   var $tf03_d_validadefim_ano = null; 
   var $tf03_d_validadefim = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf03_i_codigo = int4 = Código 
                 tf03_c_descr = varchar(100) = Descrição 
                 tf03_i_tipodistancia = int4 = Tipo Distância 
                 tf03_f_distancia = float4 = Distância 
                 tf03_d_validadeini = date = Início 
                 tf03_d_validadefim = date = Fim 
                 ";
   //funcao construtor da classe 
   function cl_tfd_destino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_destino"); 
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
       $this->tf03_i_codigo = ($this->tf03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_i_codigo"]:$this->tf03_i_codigo);
       $this->tf03_c_descr = ($this->tf03_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_c_descr"]:$this->tf03_c_descr);
       $this->tf03_i_tipodistancia = ($this->tf03_i_tipodistancia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_i_tipodistancia"]:$this->tf03_i_tipodistancia);
       $this->tf03_f_distancia = ($this->tf03_f_distancia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_f_distancia"]:$this->tf03_f_distancia);
       if($this->tf03_d_validadeini == ""){
         $this->tf03_d_validadeini_dia = ($this->tf03_d_validadeini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini_dia"]:$this->tf03_d_validadeini_dia);
         $this->tf03_d_validadeini_mes = ($this->tf03_d_validadeini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini_mes"]:$this->tf03_d_validadeini_mes);
         $this->tf03_d_validadeini_ano = ($this->tf03_d_validadeini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini_ano"]:$this->tf03_d_validadeini_ano);
         if($this->tf03_d_validadeini_dia != ""){
            $this->tf03_d_validadeini = $this->tf03_d_validadeini_ano."-".$this->tf03_d_validadeini_mes."-".$this->tf03_d_validadeini_dia;
         }
       }
       if($this->tf03_d_validadefim == ""){
         $this->tf03_d_validadefim_dia = ($this->tf03_d_validadefim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim_dia"]:$this->tf03_d_validadefim_dia);
         $this->tf03_d_validadefim_mes = ($this->tf03_d_validadefim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim_mes"]:$this->tf03_d_validadefim_mes);
         $this->tf03_d_validadefim_ano = ($this->tf03_d_validadefim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim_ano"]:$this->tf03_d_validadefim_ano);
         if($this->tf03_d_validadefim_dia != ""){
            $this->tf03_d_validadefim = $this->tf03_d_validadefim_ano."-".$this->tf03_d_validadefim_mes."-".$this->tf03_d_validadefim_dia;
         }
       }
     }else{
       $this->tf03_i_codigo = ($this->tf03_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf03_i_codigo"]:$this->tf03_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf03_i_codigo){ 
      $this->atualizacampos();
     if($this->tf03_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "tf03_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf03_i_tipodistancia == null ){ 
       $this->erro_sql = " Campo Tipo Distância nao Informado.";
       $this->erro_campo = "tf03_i_tipodistancia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf03_f_distancia == null ){ 
       $this->erro_sql = " Campo Distância nao Informado.";
       $this->erro_campo = "tf03_f_distancia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf03_d_validadeini == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "tf03_d_validadeini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf03_d_validadefim == null ){ 
       $this->tf03_d_validadefim = "null";
     }
     if($tf03_i_codigo == "" || $tf03_i_codigo == null ){
       $result = db_query("select nextval('tfd_destino_tf03_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_destino_tf03_i_codigo_seq do campo: tf03_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf03_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_destino_tf03_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf03_i_codigo)){
         $this->erro_sql = " Campo tf03_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf03_i_codigo = $tf03_i_codigo; 
       }
     }
     if(($this->tf03_i_codigo == null) || ($this->tf03_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf03_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_destino(
                                       tf03_i_codigo 
                                      ,tf03_c_descr 
                                      ,tf03_i_tipodistancia 
                                      ,tf03_f_distancia 
                                      ,tf03_d_validadeini 
                                      ,tf03_d_validadefim 
                       )
                values (
                                $this->tf03_i_codigo 
                               ,'$this->tf03_c_descr' 
                               ,$this->tf03_i_tipodistancia 
                               ,$this->tf03_f_distancia 
                               ,".($this->tf03_d_validadeini == "null" || $this->tf03_d_validadeini == ""?"null":"'".$this->tf03_d_validadeini."'")." 
                               ,".($this->tf03_d_validadefim == "null" || $this->tf03_d_validadefim == ""?"null":"'".$this->tf03_d_validadefim."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_destino ($this->tf03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_destino já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_destino ($this->tf03_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf03_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf03_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16341,'$this->tf03_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2859,16341,'','".AddSlashes(pg_result($resaco,0,'tf03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2859,16342,'','".AddSlashes(pg_result($resaco,0,'tf03_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2859,16343,'','".AddSlashes(pg_result($resaco,0,'tf03_i_tipodistancia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2859,16344,'','".AddSlashes(pg_result($resaco,0,'tf03_f_distancia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2859,16345,'','".AddSlashes(pg_result($resaco,0,'tf03_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2859,16346,'','".AddSlashes(pg_result($resaco,0,'tf03_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf03_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_destino set ";
     $virgula = "";
     if(trim($this->tf03_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf03_i_codigo"])){ 
       $sql  .= $virgula." tf03_i_codigo = $this->tf03_i_codigo ";
       $virgula = ",";
       if(trim($this->tf03_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf03_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf03_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf03_c_descr"])){ 
       $sql  .= $virgula." tf03_c_descr = '$this->tf03_c_descr' ";
       $virgula = ",";
       if(trim($this->tf03_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "tf03_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf03_i_tipodistancia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf03_i_tipodistancia"])){ 
       $sql  .= $virgula." tf03_i_tipodistancia = $this->tf03_i_tipodistancia ";
       $virgula = ",";
       if(trim($this->tf03_i_tipodistancia) == null ){ 
         $this->erro_sql = " Campo Tipo Distância nao Informado.";
         $this->erro_campo = "tf03_i_tipodistancia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf03_f_distancia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf03_f_distancia"])){ 
       $sql  .= $virgula." tf03_f_distancia = $this->tf03_f_distancia ";
       $virgula = ",";
       if(trim($this->tf03_f_distancia) == null ){ 
         $this->erro_sql = " Campo Distância nao Informado.";
         $this->erro_campo = "tf03_f_distancia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf03_d_validadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini_dia"] !="") ){ 
       $sql  .= $virgula." tf03_d_validadeini = '$this->tf03_d_validadeini' ";
       $virgula = ",";
       if(trim($this->tf03_d_validadeini) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "tf03_d_validadeini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini_dia"])){ 
         $sql  .= $virgula." tf03_d_validadeini = null ";
         $virgula = ",";
         if(trim($this->tf03_d_validadeini) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "tf03_d_validadeini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf03_d_validadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim_dia"] !="") ){ 
       $sql  .= $virgula." tf03_d_validadefim = '$this->tf03_d_validadefim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim_dia"])){ 
         $sql  .= $virgula." tf03_d_validadefim = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($tf03_i_codigo!=null){
       $sql .= " tf03_i_codigo = $this->tf03_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf03_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16341,'$this->tf03_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_i_codigo"]) || $this->tf03_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2859,16341,'".AddSlashes(pg_result($resaco,$conresaco,'tf03_i_codigo'))."','$this->tf03_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_c_descr"]) || $this->tf03_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2859,16342,'".AddSlashes(pg_result($resaco,$conresaco,'tf03_c_descr'))."','$this->tf03_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_i_tipodistancia"]) || $this->tf03_i_tipodistancia != "")
           $resac = db_query("insert into db_acount values($acount,2859,16343,'".AddSlashes(pg_result($resaco,$conresaco,'tf03_i_tipodistancia'))."','$this->tf03_i_tipodistancia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_f_distancia"]) || $this->tf03_f_distancia != "")
           $resac = db_query("insert into db_acount values($acount,2859,16344,'".AddSlashes(pg_result($resaco,$conresaco,'tf03_f_distancia'))."','$this->tf03_f_distancia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadeini"]) || $this->tf03_d_validadeini != "")
           $resac = db_query("insert into db_acount values($acount,2859,16345,'".AddSlashes(pg_result($resaco,$conresaco,'tf03_d_validadeini'))."','$this->tf03_d_validadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf03_d_validadefim"]) || $this->tf03_d_validadefim != "")
           $resac = db_query("insert into db_acount values($acount,2859,16346,'".AddSlashes(pg_result($resaco,$conresaco,'tf03_d_validadefim'))."','$this->tf03_d_validadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_destino nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_destino nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf03_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf03_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16341,'$tf03_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2859,16341,'','".AddSlashes(pg_result($resaco,$iresaco,'tf03_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2859,16342,'','".AddSlashes(pg_result($resaco,$iresaco,'tf03_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2859,16343,'','".AddSlashes(pg_result($resaco,$iresaco,'tf03_i_tipodistancia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2859,16344,'','".AddSlashes(pg_result($resaco,$iresaco,'tf03_f_distancia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2859,16345,'','".AddSlashes(pg_result($resaco,$iresaco,'tf03_d_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2859,16346,'','".AddSlashes(pg_result($resaco,$iresaco,'tf03_d_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_destino
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf03_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf03_i_codigo = $tf03_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_destino nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf03_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_destino nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf03_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf03_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_destino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_destino ";
     $sql .= "      inner join tfd_tipodistancia  on  tfd_tipodistancia.tf24_i_codigo = tfd_destino.tf03_i_tipodistancia";
     $sql2 = "";
     if($dbwhere==""){
       if($tf03_i_codigo!=null ){
         $sql2 .= " where tfd_destino.tf03_i_codigo = $tf03_i_codigo "; 
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
   function sql_query_file ( $tf03_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_destino ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf03_i_codigo!=null ){
         $sql2 .= " where tfd_destino.tf03_i_codigo = $tf03_i_codigo "; 
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