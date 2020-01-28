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

//MODULO: educação
//CLASSE DA ENTIDADE periodos
class cl_periodos { 
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
   var $ed23_i_codigo = 0; 
   var $ed23_i_anoletivo = 0; 
   var $ed23_c_nome = null; 
   var $ed23_d_inicio_dia = null; 
   var $ed23_d_inicio_mes = null; 
   var $ed23_d_inicio_ano = null; 
   var $ed23_d_inicio = null; 
   var $ed23_d_fim_dia = null; 
   var $ed23_d_fim_mes = null; 
   var $ed23_d_fim_ano = null; 
   var $ed23_d_fim = null; 
   var $ed23_c_encerrado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed23_i_codigo = int8 = Código do Período 
                 ed23_i_anoletivo = int4 = Ano Letivo 
                 ed23_c_nome = char(30) = Nome 
                 ed23_d_inicio = date = Início 
                 ed23_d_fim = date = Fim 
                 ed23_c_encerrado = bool = Encerrado 
                 ";
   //funcao construtor da classe 
   function cl_periodos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("periodos"); 
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
       $this->ed23_i_codigo = ($this->ed23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"]:$this->ed23_i_codigo);
       $this->ed23_i_anoletivo = ($this->ed23_i_anoletivo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_anoletivo"]:$this->ed23_i_anoletivo);
       $this->ed23_c_nome = ($this->ed23_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_c_nome"]:$this->ed23_c_nome);
       if($this->ed23_d_inicio == ""){
         $this->ed23_d_inicio_dia = ($this->ed23_d_inicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio_dia"]:$this->ed23_d_inicio_dia);
         $this->ed23_d_inicio_mes = ($this->ed23_d_inicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio_mes"]:$this->ed23_d_inicio_mes);
         $this->ed23_d_inicio_ano = ($this->ed23_d_inicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio_ano"]:$this->ed23_d_inicio_ano);
         if($this->ed23_d_inicio_dia != ""){
            $this->ed23_d_inicio = $this->ed23_d_inicio_ano."-".$this->ed23_d_inicio_mes."-".$this->ed23_d_inicio_dia;
         }
       }
       if($this->ed23_d_fim == ""){
         $this->ed23_d_fim_dia = ($this->ed23_d_fim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_d_fim_dia"]:$this->ed23_d_fim_dia);
         $this->ed23_d_fim_mes = ($this->ed23_d_fim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_d_fim_mes"]:$this->ed23_d_fim_mes);
         $this->ed23_d_fim_ano = ($this->ed23_d_fim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_d_fim_ano"]:$this->ed23_d_fim_ano);
         if($this->ed23_d_fim_dia != ""){
            $this->ed23_d_fim = $this->ed23_d_fim_ano."-".$this->ed23_d_fim_mes."-".$this->ed23_d_fim_dia;
         }
       }
       $this->ed23_c_encerrado = ($this->ed23_c_encerrado == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed23_c_encerrado"]:$this->ed23_c_encerrado);
     }else{
       $this->ed23_i_codigo = ($this->ed23_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"]:$this->ed23_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed23_i_codigo){ 
      $this->atualizacampos();
     if($this->ed23_i_anoletivo == null ){ 
       $this->erro_sql = " Campo Ano Letivo nao Informado.";
       $this->erro_campo = "ed23_i_anoletivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed23_c_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ed23_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed23_d_inicio == null ){ 
       $this->erro_sql = " Campo Início nao Informado.";
       $this->erro_campo = "ed23_d_inicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed23_d_fim == null ){ 
       $this->erro_sql = " Campo Fim nao Informado.";
       $this->erro_campo = "ed23_d_fim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed23_c_encerrado == null ){ 
       $this->erro_sql = " Campo Encerrado nao Informado.";
       $this->erro_campo = "ed23_c_encerrado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed23_i_codigo == "" || $ed23_i_codigo == null ){
       $result = @pg_query("select nextval('periodos_ed23_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: periodos_ed23_i_codigo_seq do campo: ed23_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed23_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from periodos_ed23_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed23_i_codigo)){
         $this->erro_sql = " Campo ed23_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed23_i_codigo = $ed23_i_codigo; 
       }
     }
     if(($this->ed23_i_codigo == null) || ($this->ed23_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed23_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into periodos(
                                       ed23_i_codigo 
                                      ,ed23_i_anoletivo 
                                      ,ed23_c_nome 
                                      ,ed23_d_inicio 
                                      ,ed23_d_fim 
                                      ,ed23_c_encerrado 
                       )
                values (
                                $this->ed23_i_codigo 
                               ,$this->ed23_i_anoletivo 
                               ,'$this->ed23_c_nome' 
                               ,".($this->ed23_d_inicio == "null" || $this->ed23_d_inicio == ""?"null":"'".$this->ed23_d_inicio."'")." 
                               ,".($this->ed23_d_fim == "null" || $this->ed23_d_fim == ""?"null":"'".$this->ed23_d_fim."'")." 
                               ,'$this->ed23_c_encerrado' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Períodos ($this->ed23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Períodos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Períodos ($this->ed23_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed23_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006068,'$this->ed23_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006010,1006068,'','".AddSlashes(pg_result($resaco,0,'ed23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006010,1006161,'','".AddSlashes(pg_result($resaco,0,'ed23_i_anoletivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006010,1006069,'','".AddSlashes(pg_result($resaco,0,'ed23_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006010,1006162,'','".AddSlashes(pg_result($resaco,0,'ed23_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006010,1006163,'','".AddSlashes(pg_result($resaco,0,'ed23_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006010,1006164,'','".AddSlashes(pg_result($resaco,0,'ed23_c_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed23_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update periodos set ";
     $virgula = "";
     if(trim($this->ed23_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"])){ 
       $sql  .= $virgula." ed23_i_codigo = $this->ed23_i_codigo ";
       $virgula = ",";
       if(trim($this->ed23_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Período nao Informado.";
         $this->erro_campo = "ed23_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed23_i_anoletivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_anoletivo"])){ 
       $sql  .= $virgula." ed23_i_anoletivo = $this->ed23_i_anoletivo ";
       $virgula = ",";
       if(trim($this->ed23_i_anoletivo) == null ){ 
         $this->erro_sql = " Campo Ano Letivo nao Informado.";
         $this->erro_campo = "ed23_i_anoletivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed23_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_c_nome"])){ 
       $sql  .= $virgula." ed23_c_nome = '$this->ed23_c_nome' ";
       $virgula = ",";
       if(trim($this->ed23_c_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ed23_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed23_d_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio_dia"] !="") ){ 
       $sql  .= $virgula." ed23_d_inicio = '$this->ed23_d_inicio' ";
       $virgula = ",";
       if(trim($this->ed23_d_inicio) == null ){ 
         $this->erro_sql = " Campo Início nao Informado.";
         $this->erro_campo = "ed23_d_inicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio_dia"])){ 
         $sql  .= $virgula." ed23_d_inicio = null ";
         $virgula = ",";
         if(trim($this->ed23_d_inicio) == null ){ 
           $this->erro_sql = " Campo Início nao Informado.";
           $this->erro_campo = "ed23_d_inicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed23_d_fim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_d_fim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed23_d_fim_dia"] !="") ){ 
       $sql  .= $virgula." ed23_d_fim = '$this->ed23_d_fim' ";
       $virgula = ",";
       if(trim($this->ed23_d_fim) == null ){ 
         $this->erro_sql = " Campo Fim nao Informado.";
         $this->erro_campo = "ed23_d_fim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_d_fim_dia"])){ 
         $sql  .= $virgula." ed23_d_fim = null ";
         $virgula = ",";
         if(trim($this->ed23_d_fim) == null ){ 
           $this->erro_sql = " Campo Fim nao Informado.";
           $this->erro_campo = "ed23_d_fim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ed23_c_encerrado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed23_c_encerrado"])){ 
       $sql  .= $virgula." ed23_c_encerrado = '$this->ed23_c_encerrado' ";
       $virgula = ",";
       if(trim($this->ed23_c_encerrado) == null ){ 
         $this->erro_sql = " Campo Encerrado nao Informado.";
         $this->erro_campo = "ed23_c_encerrado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed23_i_codigo!=null){
       $sql .= " ed23_i_codigo = $this->ed23_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed23_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006068,'$this->ed23_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006010,1006068,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_codigo'))."','$this->ed23_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_i_anoletivo"]))
           $resac = pg_query("insert into db_acount values($acount,1006010,1006161,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_i_anoletivo'))."','$this->ed23_i_anoletivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_c_nome"]))
           $resac = pg_query("insert into db_acount values($acount,1006010,1006069,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_c_nome'))."','$this->ed23_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_d_inicio"]))
           $resac = pg_query("insert into db_acount values($acount,1006010,1006162,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_d_inicio'))."','$this->ed23_d_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_d_fim"]))
           $resac = pg_query("insert into db_acount values($acount,1006010,1006163,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_d_fim'))."','$this->ed23_d_fim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed23_c_encerrado"]))
           $resac = pg_query("insert into db_acount values($acount,1006010,1006164,'".AddSlashes(pg_result($resaco,$conresaco,'ed23_c_encerrado'))."','$this->ed23_c_encerrado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Períodos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Períodos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed23_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed23_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006068,'$ed23_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006010,1006068,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006010,1006161,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_i_anoletivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006010,1006069,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006010,1006162,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_d_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006010,1006163,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_d_fim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006010,1006164,'','".AddSlashes(pg_result($resaco,$iresaco,'ed23_c_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from periodos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed23_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed23_i_codigo = $ed23_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Períodos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed23_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Períodos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed23_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed23_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:periodos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodos ";
     $sql .= "      inner join anoletivo  on  anoletivo.ed28_i_codigo = periodos.ed23_i_anoletivo";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = anoletivo.ed28_i_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($ed23_i_codigo!=null ){
         $sql2 .= " where periodos.ed23_i_codigo = $ed23_i_codigo "; 
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
   function sql_query_file ( $ed23_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from periodos ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed23_i_codigo!=null ){
         $sql2 .= " where periodos.ed23_i_codigo = $ed23_i_codigo "; 
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