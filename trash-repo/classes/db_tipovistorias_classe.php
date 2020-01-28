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

//MODULO: fiscal
//CLASSE DA ENTIDADE tipovistorias
class cl_tipovistorias { 
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
   var $y77_codtipo = 0; 
   var $y77_descricao = null; 
   var $y77_obs = null; 
   var $y77_coddepto = 0; 
   var $y77_tipoandam = 0; 
   var $y77_dias = 0; 
   var $y77_diasgeral = 0; 
   var $y77_mesgeral = 0; 
   var $y77_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 y77_codtipo = int4 = Código do Tipo 
                 y77_descricao = varchar(50) = Descrição da Vistoria 
                 y77_obs = text = Observação da Vistoria 
                 y77_coddepto = int4 = Código do Departamento 
                 y77_tipoandam = int4 = Código do Tipo de Andamento 
                 y77_dias = int4 = Quantidade de dias para o vencimento 
                 y77_diasgeral = int4 = Dia para o vencimento 
                 y77_mesgeral = int4 = Mes para o vencimento 
                 y77_instit = int4 = Cod. Instituição 
                 ";
   //funcao construtor da classe 
   function cl_tipovistorias() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tipovistorias"); 
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
       $this->y77_codtipo = ($this->y77_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_codtipo"]:$this->y77_codtipo);
       $this->y77_descricao = ($this->y77_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_descricao"]:$this->y77_descricao);
       $this->y77_obs = ($this->y77_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_obs"]:$this->y77_obs);
       $this->y77_coddepto = ($this->y77_coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_coddepto"]:$this->y77_coddepto);
       $this->y77_tipoandam = ($this->y77_tipoandam == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_tipoandam"]:$this->y77_tipoandam);
       $this->y77_dias = ($this->y77_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_dias"]:$this->y77_dias);
       $this->y77_diasgeral = ($this->y77_diasgeral == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_diasgeral"]:$this->y77_diasgeral);
       $this->y77_mesgeral = ($this->y77_mesgeral == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_mesgeral"]:$this->y77_mesgeral);
       $this->y77_instit = ($this->y77_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_instit"]:$this->y77_instit);
     }else{
       $this->y77_codtipo = ($this->y77_codtipo == ""?@$GLOBALS["HTTP_POST_VARS"]["y77_codtipo"]:$this->y77_codtipo);
     }
   }
   // funcao para inclusao
   function incluir ($y77_codtipo){ 
      $this->atualizacampos();
     if($this->y77_descricao == null ){ 
       $this->erro_sql = " Campo Descrição da Vistoria nao Informado.";
       $this->erro_campo = "y77_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y77_coddepto == null ){ 
       $this->erro_sql = " Campo Código do Departamento nao Informado.";
       $this->erro_campo = "y77_coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y77_tipoandam == null ){ 
       $this->erro_sql = " Campo Código do Tipo de Andamento nao Informado.";
       $this->erro_campo = "y77_tipoandam";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y77_dias == null ){ 
       $this->y77_dias = "0";
     }
     if($this->y77_diasgeral == null ){ 
       $this->erro_sql = " Campo Dia para o vencimento nao Informado.";
       $this->erro_campo = "y77_diasgeral";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y77_mesgeral == null ){ 
       $this->erro_sql = " Campo Mes para o vencimento nao Informado.";
       $this->erro_campo = "y77_mesgeral";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->y77_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição nao Informado.";
       $this->erro_campo = "y77_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($y77_codtipo == "" || $y77_codtipo == null ){
       $result = db_query("select nextval('tipovistorias_y77_codtipo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tipovistorias_y77_codtipo_seq do campo: y77_codtipo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->y77_codtipo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tipovistorias_y77_codtipo_seq");
       if(($result != false) && (pg_result($result,0,0) < $y77_codtipo)){
         $this->erro_sql = " Campo y77_codtipo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->y77_codtipo = $y77_codtipo; 
       }
     }
     if(($this->y77_codtipo == null) || ($this->y77_codtipo == "") ){ 
       $this->erro_sql = " Campo y77_codtipo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tipovistorias(
                                       y77_codtipo 
                                      ,y77_descricao 
                                      ,y77_obs 
                                      ,y77_coddepto 
                                      ,y77_tipoandam 
                                      ,y77_dias 
                                      ,y77_diasgeral 
                                      ,y77_mesgeral 
                                      ,y77_instit 
                       )
                values (
                                $this->y77_codtipo 
                               ,'$this->y77_descricao' 
                               ,'$this->y77_obs' 
                               ,$this->y77_coddepto 
                               ,$this->y77_tipoandam 
                               ,$this->y77_dias 
                               ,$this->y77_diasgeral 
                               ,$this->y77_mesgeral 
                               ,$this->y77_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tipovistorias ($this->y77_codtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tipovistorias já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tipovistorias ($this->y77_codtipo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y77_codtipo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->y77_codtipo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4892,'$this->y77_codtipo','I')");
       $resac = db_query("insert into db_acount values($acount,667,4892,'','".AddSlashes(pg_result($resaco,0,'y77_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,4893,'','".AddSlashes(pg_result($resaco,0,'y77_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,4894,'','".AddSlashes(pg_result($resaco,0,'y77_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,4895,'','".AddSlashes(pg_result($resaco,0,'y77_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,5062,'','".AddSlashes(pg_result($resaco,0,'y77_tipoandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,5198,'','".AddSlashes(pg_result($resaco,0,'y77_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,8335,'','".AddSlashes(pg_result($resaco,0,'y77_diasgeral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,8336,'','".AddSlashes(pg_result($resaco,0,'y77_mesgeral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,667,10662,'','".AddSlashes(pg_result($resaco,0,'y77_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($y77_codtipo=null) { 
      $this->atualizacampos();
     $sql = " update tipovistorias set ";
     $virgula = "";
     if(trim($this->y77_codtipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_codtipo"])){ 
       $sql  .= $virgula." y77_codtipo = $this->y77_codtipo ";
       $virgula = ",";
       if(trim($this->y77_codtipo) == null ){ 
         $this->erro_sql = " Campo Código do Tipo nao Informado.";
         $this->erro_campo = "y77_codtipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y77_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_descricao"])){ 
       $sql  .= $virgula." y77_descricao = '$this->y77_descricao' ";
       $virgula = ",";
       if(trim($this->y77_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição da Vistoria nao Informado.";
         $this->erro_campo = "y77_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y77_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_obs"])){ 
       $sql  .= $virgula." y77_obs = '$this->y77_obs' ";
       $virgula = ",";
     }
     if(trim($this->y77_coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_coddepto"])){ 
       $sql  .= $virgula." y77_coddepto = $this->y77_coddepto ";
       $virgula = ",";
       if(trim($this->y77_coddepto) == null ){ 
         $this->erro_sql = " Campo Código do Departamento nao Informado.";
         $this->erro_campo = "y77_coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y77_tipoandam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_tipoandam"])){ 
       $sql  .= $virgula." y77_tipoandam = $this->y77_tipoandam ";
       $virgula = ",";
       if(trim($this->y77_tipoandam) == null ){ 
         $this->erro_sql = " Campo Código do Tipo de Andamento nao Informado.";
         $this->erro_campo = "y77_tipoandam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y77_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_dias"])){ 
        if(trim($this->y77_dias)=="" && isset($GLOBALS["HTTP_POST_VARS"]["y77_dias"])){ 
           $this->y77_dias = "0" ; 
        } 
       $sql  .= $virgula." y77_dias = $this->y77_dias ";
       $virgula = ",";
     }
     if(trim($this->y77_diasgeral)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_diasgeral"])){ 
       $sql  .= $virgula." y77_diasgeral = $this->y77_diasgeral ";
       $virgula = ",";
       if(trim($this->y77_diasgeral) == null ){ 
         $this->erro_sql = " Campo Dia para o vencimento nao Informado.";
         $this->erro_campo = "y77_diasgeral";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y77_mesgeral)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_mesgeral"])){ 
       $sql  .= $virgula." y77_mesgeral = $this->y77_mesgeral ";
       $virgula = ",";
       if(trim($this->y77_mesgeral) == null ){ 
         $this->erro_sql = " Campo Mes para o vencimento nao Informado.";
         $this->erro_campo = "y77_mesgeral";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->y77_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["y77_instit"])){ 
       $sql  .= $virgula." y77_instit = $this->y77_instit ";
       $virgula = ",";
       if(trim($this->y77_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "y77_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($y77_codtipo!=null){
       $sql .= " y77_codtipo = $this->y77_codtipo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->y77_codtipo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4892,'$this->y77_codtipo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_codtipo"]))
           $resac = db_query("insert into db_acount values($acount,667,4892,'".AddSlashes(pg_result($resaco,$conresaco,'y77_codtipo'))."','$this->y77_codtipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_descricao"]))
           $resac = db_query("insert into db_acount values($acount,667,4893,'".AddSlashes(pg_result($resaco,$conresaco,'y77_descricao'))."','$this->y77_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_obs"]))
           $resac = db_query("insert into db_acount values($acount,667,4894,'".AddSlashes(pg_result($resaco,$conresaco,'y77_obs'))."','$this->y77_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_coddepto"]))
           $resac = db_query("insert into db_acount values($acount,667,4895,'".AddSlashes(pg_result($resaco,$conresaco,'y77_coddepto'))."','$this->y77_coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_tipoandam"]))
           $resac = db_query("insert into db_acount values($acount,667,5062,'".AddSlashes(pg_result($resaco,$conresaco,'y77_tipoandam'))."','$this->y77_tipoandam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_dias"]))
           $resac = db_query("insert into db_acount values($acount,667,5198,'".AddSlashes(pg_result($resaco,$conresaco,'y77_dias'))."','$this->y77_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_diasgeral"]))
           $resac = db_query("insert into db_acount values($acount,667,8335,'".AddSlashes(pg_result($resaco,$conresaco,'y77_diasgeral'))."','$this->y77_diasgeral',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_mesgeral"]))
           $resac = db_query("insert into db_acount values($acount,667,8336,'".AddSlashes(pg_result($resaco,$conresaco,'y77_mesgeral'))."','$this->y77_mesgeral',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["y77_instit"]))
           $resac = db_query("insert into db_acount values($acount,667,10662,'".AddSlashes(pg_result($resaco,$conresaco,'y77_instit'))."','$this->y77_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipovistorias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->y77_codtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tipovistorias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->y77_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->y77_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($y77_codtipo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($y77_codtipo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4892,'$y77_codtipo','E')");
         $resac = db_query("insert into db_acount values($acount,667,4892,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_codtipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,4893,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,4894,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,4895,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,5062,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_tipoandam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,5198,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,8335,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_diasgeral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,8336,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_mesgeral'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,667,10662,'','".AddSlashes(pg_result($resaco,$iresaco,'y77_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tipovistorias
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($y77_codtipo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " y77_codtipo = $y77_codtipo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tipovistorias nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$y77_codtipo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tipovistorias nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$y77_codtipo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$y77_codtipo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tipovistorias";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $y77_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipovistorias ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = tipovistorias.y77_coddepto";
     $sql .= "      inner join tipoandam  on  tipoandam.y41_codtipo = tipovistorias.y77_tipoandam";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql2 = "";
     if($dbwhere==""){
       if($y77_codtipo!=null ){
         $sql2 .= " where tipovistorias.y77_codtipo = $y77_codtipo "; 
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
   function sql_query_file ( $y77_codtipo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tipovistorias ";
     $sql2 = "";
     if($dbwhere==""){
       if($y77_codtipo!=null ){
         $sql2 .= " where tipovistorias.y77_codtipo = $y77_codtipo "; 
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