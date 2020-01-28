<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: agua
//CLASSE DA ENTIDADE aguaconsumo
class cl_aguaconsumo { 
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
   var $x19_codconsumo = 0; 
   var $x19_exerc = 0; 
   var $x19_areaini = 0; 
   var $x19_areafim = 0; 
   var $x19_caract = 0; 
   var $x19_conspadrao = 0; 
   var $x19_descr = null; 
   var $x19_ativo = 'f'; 
   var $x19_zona = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x19_codconsumo = int4 = Codigo 
                 x19_exerc = int4 = Ano 
                 x19_areaini = float4 = Area Inicial 
                 x19_areafim = float4 = Area Final 
                 x19_caract = int4 = Caracteristica 
                 x19_conspadrao = float8 = Consumo Padrao 
                 x19_descr = varchar(40) = Descricao 
                 x19_ativo = bool = Ativo 
                 x19_zona = int4 = Zona 
                 ";
   //funcao construtor da classe 
   function cl_aguaconsumo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguaconsumo"); 
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
       $this->x19_codconsumo = ($this->x19_codconsumo == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_codconsumo"]:$this->x19_codconsumo);
       $this->x19_exerc = ($this->x19_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_exerc"]:$this->x19_exerc);
       $this->x19_areaini = ($this->x19_areaini == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_areaini"]:$this->x19_areaini);
       $this->x19_areafim = ($this->x19_areafim == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_areafim"]:$this->x19_areafim);
       $this->x19_caract = ($this->x19_caract == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_caract"]:$this->x19_caract);
       $this->x19_conspadrao = ($this->x19_conspadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_conspadrao"]:$this->x19_conspadrao);
       $this->x19_descr = ($this->x19_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_descr"]:$this->x19_descr);
       $this->x19_ativo = ($this->x19_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["x19_ativo"]:$this->x19_ativo);
       $this->x19_zona = ($this->x19_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_zona"]:$this->x19_zona);
     }else{
       $this->x19_codconsumo = ($this->x19_codconsumo == ""?@$GLOBALS["HTTP_POST_VARS"]["x19_codconsumo"]:$this->x19_codconsumo);
     }
   }
   // funcao para inclusao
   function incluir ($x19_codconsumo){ 
      $this->atualizacampos();
     if($this->x19_exerc == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "x19_exerc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x19_areaini == null ){ 
       $this->erro_sql = " Campo Area Inicial nao Informado.";
       $this->erro_campo = "x19_areaini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x19_areafim == null ){ 
       $this->erro_sql = " Campo Area Final nao Informado.";
       $this->erro_campo = "x19_areafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x19_caract == null ){ 
       $this->x19_caract = "0";
     }
     if($this->x19_conspadrao == null ){ 
       $this->erro_sql = " Campo Consumo Padrao nao Informado.";
       $this->erro_campo = "x19_conspadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x19_descr == null ){ 
       $this->erro_sql = " Campo Descricao nao Informado.";
       $this->erro_campo = "x19_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x19_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "x19_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x19_zona == null ){ 
       $this->erro_sql = " Campo Zona nao Informado.";
       $this->erro_campo = "x19_zona";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($x19_codconsumo == "" || $x19_codconsumo == null ){
       $result = db_query("select nextval('aguaconsumo_x19_codconsumo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: aguaconsumo_x19_codconsumo_seq do campo: x19_codconsumo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->x19_codconsumo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from aguaconsumo_x19_codconsumo_seq");
       if(($result != false) && (pg_result($result,0,0) < $x19_codconsumo)){
         $this->erro_sql = " Campo x19_codconsumo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->x19_codconsumo = $x19_codconsumo; 
       }
     }
     if(($this->x19_codconsumo == null) || ($this->x19_codconsumo == "") ){ 
       $this->erro_sql = " Campo x19_codconsumo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into aguaconsumo(
                                       x19_codconsumo 
                                      ,x19_exerc 
                                      ,x19_areaini 
                                      ,x19_areafim 
                                      ,x19_caract 
                                      ,x19_conspadrao 
                                      ,x19_descr 
                                      ,x19_ativo 
                                      ,x19_zona 
                       )
                values (
                                $this->x19_codconsumo 
                               ,$this->x19_exerc 
                               ,$this->x19_areaini 
                               ,$this->x19_areafim 
                               ,$this->x19_caract 
                               ,$this->x19_conspadrao 
                               ,'$this->x19_descr' 
                               ,'$this->x19_ativo' 
                               ,$this->x19_zona 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Categorias de Consumo ($this->x19_codconsumo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Categorias de Consumo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Categorias de Consumo ($this->x19_codconsumo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x19_codconsumo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->x19_codconsumo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8480,'$this->x19_codconsumo','I')");
       $resac = db_query("insert into db_acount values($acount,1441,8480,'','".AddSlashes(pg_result($resaco,0,'x19_codconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8481,'','".AddSlashes(pg_result($resaco,0,'x19_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8482,'','".AddSlashes(pg_result($resaco,0,'x19_areaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8483,'','".AddSlashes(pg_result($resaco,0,'x19_areafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8484,'','".AddSlashes(pg_result($resaco,0,'x19_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8485,'','".AddSlashes(pg_result($resaco,0,'x19_conspadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8486,'','".AddSlashes(pg_result($resaco,0,'x19_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8487,'','".AddSlashes(pg_result($resaco,0,'x19_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1441,8779,'','".AddSlashes(pg_result($resaco,0,'x19_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($x19_codconsumo=null) { 
      $this->atualizacampos();
     $sql = " update aguaconsumo set ";
     $virgula = "";
     if(trim($this->x19_codconsumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_codconsumo"])){ 
       $sql  .= $virgula." x19_codconsumo = $this->x19_codconsumo ";
       $virgula = ",";
       if(trim($this->x19_codconsumo) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "x19_codconsumo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_exerc"])){ 
       $sql  .= $virgula." x19_exerc = $this->x19_exerc ";
       $virgula = ",";
       if(trim($this->x19_exerc) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "x19_exerc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_areaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_areaini"])){ 
       $sql  .= $virgula." x19_areaini = $this->x19_areaini ";
       $virgula = ",";
       if(trim($this->x19_areaini) == null ){ 
         $this->erro_sql = " Campo Area Inicial nao Informado.";
         $this->erro_campo = "x19_areaini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_areafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_areafim"])){ 
       $sql  .= $virgula." x19_areafim = $this->x19_areafim ";
       $virgula = ",";
       if(trim($this->x19_areafim) == null ){ 
         $this->erro_sql = " Campo Area Final nao Informado.";
         $this->erro_campo = "x19_areafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_caract)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_caract"])){ 
        if(trim($this->x19_caract)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x19_caract"])){ 
           $this->x19_caract = "0" ; 
        } 
       $sql  .= $virgula." x19_caract = $this->x19_caract ";
       $virgula = ",";
     }
     if(trim($this->x19_conspadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_conspadrao"])){ 
       $sql  .= $virgula." x19_conspadrao = $this->x19_conspadrao ";
       $virgula = ",";
       if(trim($this->x19_conspadrao) == null ){ 
         $this->erro_sql = " Campo Consumo Padrao nao Informado.";
         $this->erro_campo = "x19_conspadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_descr"])){ 
       $sql  .= $virgula." x19_descr = '$this->x19_descr' ";
       $virgula = ",";
       if(trim($this->x19_descr) == null ){ 
         $this->erro_sql = " Campo Descricao nao Informado.";
         $this->erro_campo = "x19_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_ativo"])){ 
       $sql  .= $virgula." x19_ativo = '$this->x19_ativo' ";
       $virgula = ",";
       if(trim($this->x19_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "x19_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x19_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x19_zona"])){ 
       $sql  .= $virgula." x19_zona = $this->x19_zona ";
       $virgula = ",";
       if(trim($this->x19_zona) == null ){ 
         $this->erro_sql = " Campo Zona nao Informado.";
         $this->erro_campo = "x19_zona";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($x19_codconsumo!=null){
       $sql .= " x19_codconsumo = $this->x19_codconsumo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->x19_codconsumo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8480,'$this->x19_codconsumo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_codconsumo"]) || $this->x19_codconsumo != "")
           $resac = db_query("insert into db_acount values($acount,1441,8480,'".AddSlashes(pg_result($resaco,$conresaco,'x19_codconsumo'))."','$this->x19_codconsumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_exerc"]) || $this->x19_exerc != "")
           $resac = db_query("insert into db_acount values($acount,1441,8481,'".AddSlashes(pg_result($resaco,$conresaco,'x19_exerc'))."','$this->x19_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_areaini"]) || $this->x19_areaini != "")
           $resac = db_query("insert into db_acount values($acount,1441,8482,'".AddSlashes(pg_result($resaco,$conresaco,'x19_areaini'))."','$this->x19_areaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_areafim"]) || $this->x19_areafim != "")
           $resac = db_query("insert into db_acount values($acount,1441,8483,'".AddSlashes(pg_result($resaco,$conresaco,'x19_areafim'))."','$this->x19_areafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_caract"]) || $this->x19_caract != "")
           $resac = db_query("insert into db_acount values($acount,1441,8484,'".AddSlashes(pg_result($resaco,$conresaco,'x19_caract'))."','$this->x19_caract',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_conspadrao"]) || $this->x19_conspadrao != "")
           $resac = db_query("insert into db_acount values($acount,1441,8485,'".AddSlashes(pg_result($resaco,$conresaco,'x19_conspadrao'))."','$this->x19_conspadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_descr"]) || $this->x19_descr != "")
           $resac = db_query("insert into db_acount values($acount,1441,8486,'".AddSlashes(pg_result($resaco,$conresaco,'x19_descr'))."','$this->x19_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_ativo"]) || $this->x19_ativo != "")
           $resac = db_query("insert into db_acount values($acount,1441,8487,'".AddSlashes(pg_result($resaco,$conresaco,'x19_ativo'))."','$this->x19_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["x19_zona"]) || $this->x19_zona != "")
           $resac = db_query("insert into db_acount values($acount,1441,8779,'".AddSlashes(pg_result($resaco,$conresaco,'x19_zona'))."','$this->x19_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Categorias de Consumo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->x19_codconsumo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Categorias de Consumo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->x19_codconsumo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->x19_codconsumo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($x19_codconsumo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($x19_codconsumo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8480,'$x19_codconsumo','E')");
         $resac = db_query("insert into db_acount values($acount,1441,8480,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_codconsumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8481,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8482,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_areaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8483,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_areafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8484,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_caract'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8485,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_conspadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8486,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8487,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1441,8779,'','".AddSlashes(pg_result($resaco,$iresaco,'x19_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from aguaconsumo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($x19_codconsumo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " x19_codconsumo = $x19_codconsumo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Categorias de Consumo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$x19_codconsumo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Categorias de Consumo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$x19_codconsumo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$x19_codconsumo;
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
        $this->erro_sql   = "Record Vazio na Tabela:aguaconsumo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $x19_codconsumo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconsumo ";
     $sql .= "      left  join caracter  on  caracter.j31_codigo = aguaconsumo.x19_caract";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($x19_codconsumo!=null ){
         $sql2 .= " where aguaconsumo.x19_codconsumo = $x19_codconsumo "; 
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
   function sql_query_file ( $x19_codconsumo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguaconsumo ";
     $sql2 = "";
     if($dbwhere==""){
       if($x19_codconsumo!=null ){
         $sql2 .= " where aguaconsumo.x19_codconsumo = $x19_codconsumo "; 
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